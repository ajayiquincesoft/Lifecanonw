@extends('user.layout.app')
@section('content')
<!-- Page content -->
<div class="sign-sec-availablity new-clt chat-message">
	<div class="container">
				 <div class="row">
					   <div class="col-lg-12">
						<a class="back-btn" href="{{route('user.dashboard')}}"><i class="fa fa-chevron-left"></i> Back</a>
						<h2>Messages</h2>
						
					   </div><!-- col end here -->
					</div>
					<div class="row">
						<div class="col-lg-2 col-md-1"></div>
						<div class="col-lg-8 col-md-10" id="chat"> 
							<div id="messages">
							 
							</div>
							<form id="message-form">
							<div class="ms-chat">
							 <input id="message-input" class="form-control" type="" name="text" placeholder="Type your message..." required >
							 <button id="message-btn" type="submit"><i class="fa fa-paper-plane"></i></button>    
							</div><!-- ms-chat end here -->
							</form>
						</div>
					</div>
	</div>
</div><!-- sign-sec end here -->
@endsection

@section('script')
<!-- firebase integration started -->
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js"></script>
<script src="https://momentjs.com/downloads/moment.min.js"></script>
<script>
// Your web app's Firebase configuration
$(document).ready(function(){
const firebaseConfig = {

  apiKey: "AIzaSyCDUZZYqPaWx_F0w3lPWp4nFGRAtSpMzjw",

  authDomain: "life-canon.firebaseapp.com",

  databaseURL: "https://life-canon-default-rtdb.firebaseio.com",

  projectId: "life-canon",

  storageBucket: "life-canon.appspot.com",

  messagingSenderId: "56454234316",

  appId: "1:56454234316:web:7c8f180d62556a7ef44269",

  measurementId: "G-69QDKC62N7"

};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// initialize database
const db = firebase.database();

// get user's data
//const username = prompt("Please Tell Us Your Name");

// submit form
// listen for submit event on the form and call the postChat function
document.getElementById("message-form").addEventListener("submit", sendMessage);
@php $user_id = Auth::user()->id; @endphp;
 const user = {{ $user_id }};
  var room_id = {{ $chatroom_id }}; 
// send message to db
function sendMessage(e) {
  e.preventDefault();

  // get values to be submitted
  const timestamp = Date.now();
  const messageInput = document.getElementById("message-input");
  const text = messageInput.value;

  // clear the input box
  messageInput.value = "";

  //auto scroll to bottom
  document
    .getElementById("messages")
    .scrollIntoView({ behavior: "smooth", block: "end", inline: "nearest" });

  // create db collection and send in the data
 
  db.ref("messages/" + timestamp).set({
    room_id,
    text,
	timestamp,
	user
  });
}



// display the messages
// reference the collection created earlier
const fetchChat = db.ref("messages/").limitToLast(20);

// check for new messages using the onChildAdded event listener
fetchChat.on("child_added", function (snapshot) {
  const messages = snapshot.val();
  var dtime = new Date(messages.timestamp);
  var dtime2 = moment(messages.timestamp).format("ddd, MMMM D, YYYY");
  if((user === messages.user) && (messages.room_id==room_id) ){
	  
	  
  const message = `<p class=${
    user === messages.user ? "sent" : "receive"
  }><span>${messages.text}</span>${dtime2}</p>`;
  // append the message on the page
  document.getElementById("messages").innerHTML += message;
}
});
});
</script>
@stop