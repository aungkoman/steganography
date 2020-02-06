var cfg = {'iceServers': [{'url': 'stun:23.21.150.121'}]};
var con = { 'optional': [{'DtlsSrtpKeyAgreement': true}] };
var pc1 = new RTCPeerConnection(cfg, con);
var pc2 = new RTCPeerConnection(cfg, con);
var sdpConstraints = {
  optional: [],
  mandatory: {
    OfferToReceiveAudio: true,
    OfferToReceiveVideo: true
  }
};


// RTC Peer Connection

pc1.onicecandidate = function (e) {
  console.log('ICE candidate (pc1)', e)
  if (e.candidate == null) {
    $('#localOffer').html(JSON.stringify(pc1.localDescription))
  }
};


function handleOnaddstream (e) {
  console.log('Got remote stream', e.stream);
  var el = document.getElementById('remoteVideo');
  el.autoplay = true;

  // this magic function :D :D :D 
  // wtc attachMediastream
  attachMediaStream(el, e.stream);
}



function handleOnconnection () {
  console.log('Datachannel connected')
  //writeToChatLog('Datachannel connected', 'text-success')
  //$('#waitForConnection').modal('hide')
  // If we didn't call remove() here, there would be a race on pc2:
  //   - first onconnection() hides the dialog, then someone clicks
  //     on answerSentBtn which shows it, and it stays shown forever.
  //$('#waitForConnection').remove()
  //$('#showLocalAnswer').modal('hide')
  //$('#messageTextBox').focus()
}


pc1.onconnection = handleOnconnection;


function onsignalingstatechange (state) {
  console.info('signaling state change:', state)
}

function oniceconnectionstatechange (state) {
  console.info('ice connection state change:', state)
}

function onicegatheringstatechange (state) {
  console.info('ice gathering state change:', state)
}



pc1.onsignalingstatechange = onsignalingstatechange;
pc1.oniceconnectionstatechange = oniceconnectionstatechange;
pc1.onicegatheringstatechange = onicegatheringstatechange;



function handleAnswerFromPC2 (answerDesc) {
  console.log('Received remote answer: ', answerDesc)
  //writeToChatLog('Received remote answer', 'text-success')
  pc1.setRemoteDescription(answerDesc)
}

function handleCandidateFromPC2 (iceCandidate) {
  pc1.addIceCandidate(iceCandidate)
}



function handleOfferFromPC1 (offerDesc) {
  pc2.setRemoteDescription(offerDesc)
  pc2.createAnswer(function (answerDesc) {
    writeToChatLog('Created local answer', 'text-success')
    console.log('Created local answer: ', answerDesc)
    pc2.setLocalDescription(answerDesc)
  },
  function () { console.warn("Couldn't create offer") },
  sdpConstraints)
}

pc2.onicecandidate = function (e) {
  console.log('ICE candidate (pc2)', e)
  if (e.candidate == null) {
    $('#localAnswer').html(JSON.stringify(pc2.localDescription))
  }
}

pc2.onsignalingstatechange = onsignalingstatechange
pc2.oniceconnectionstatechange = oniceconnectionstatechange
pc2.onicegatheringstatechange = onicegatheringstatechange

function handleCandidateFromPC1 (iceCandidate) {
  pc2.addIceCandidate(iceCandidate)
}

pc2.onaddstream = handleOnaddstream
pc2.onconnection = handleOnconnection


// set local media to local vide 
$('#play_local_video_button').click(function () {
	console.log("RTC=> play_local_video is clicked");
	play_local_video();
});


function play_local_video(){

	/*
  navigator.getUserMedia = navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia ;

  navigator.getUserMedia({video: true, audio: true}, function (stream) {
    var video = document.getElementById('localVideo');
    video.src = window.URL.createObjectURL(stream);
    video.play();
    //pc2.addStream(stream);
  }, function (error) {
    console.log('Error adding stream to pc2: ' + error)
  }) ;

  */

    var video = document.getElementById('localVideo');
      navigator.getMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    navigator.getMedia
    (
      {
        video: true,
        audio: false
      },
      function(stream)
      {
        //alert("user accept our request");
        console.log(JSON.stringify(stream));
        //video.src = window.URL.createObjectURL(stream.url);
        video.srcObject = stream;
        //video.src = stream;
        video.play();
        /** we need to add stream into remote peer connection  **/
    	//pc2.addStream(stream);
      },
      function(err)
        {
           alert("user does not accept camera request");
          console.log("An error occured! " + err);
        }
    );

  
}

console.log("good luck RTC");