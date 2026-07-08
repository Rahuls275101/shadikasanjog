<?php 
use App\Models\Commanmodel;
$commanmodel = new Commanmodel();
$session = session();
$usersession = $session->get('loggedin');
$userdata = $commanmodel->get_single_query('user_account',array('account_id'=> $usersession['user_id']));
?>
<section>
    <div class="db">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-lg-3">
                    <?php echo view('frontend/dashboard/sidebar'); ?>
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="row">
                        <div class="col-md-12 db-sec-com">
                            <h2 class="db-tit">Chat with Interested Users</h2>
                            <div class="db-pro-stat">
                                <div class="db-chat">
                                    <ul>
                                        <?php if(!empty($chatPartners)): ?>
                                            <?php foreach($chatPartners as $chatPartnersrow) { ?>
                                            <li class="db-chat-trig" 
                                                data-partner-id="<?php echo $chatPartnersrow->partner_account_id; ?>" 
                                                data-partner-name="<?php echo $chatPartnersrow->partner_name . ' ' . $chatPartnersrow->partner_last_name; ?>">
                                                <div class="db-chat-pro"> 
                                                    <img src="<?php echo $commanmodel->profile_image($chatPartnersrow->partner_account_id); ?>" alt="<?php echo $chatPartnersrow->partner_name; ?>">
                                                </div>
                                                <div class="db-chat-bio">
                                                    <h5><?php echo $chatPartnersrow->partner_name . ' ' . $chatPartnersrow->partner_last_name; ?></h5>
                                                    <span class="last-message">
                                                        <?php 
                                                        if(!empty($chatPartnersrow->partner_message)) {
                                                            echo substr($chatPartnersrow->partner_message, 0, 30) . (strlen($chatPartnersrow->partner_message) > 30 ? '...' : '');
                                                        } else {
                                                            echo 'Start a conversation...';
                                                        }
                                                        ?>
                                                    </span>
                                                </div>
                                                <div class="db-chat-info">
                                                    <div class="time new">
                                                        <?php if(!empty($chatPartnersrow->partner_time)): ?>
                                                            <span class="timer"><?php echo date('M j, g:i A', strtotime($chatPartnersrow->partner_time)); ?></span>
                                                        <?php endif; ?>
                                                      
                                                    </div>
                                                </div>
                                            </li>
                                            <?php } ?>
                                        <?php else: ?>
                                            <li class="no-chats">
                                                <div class="db-chat-bio">
                                                    <h5>No interested users yet</h5>
                                                    <span>When users show interest in you, they will appear here for chatting!</span>
                                                </div>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CHAT CONVERSATION BOX START -->
<div class="chatbox">
    <span class="comm-msg-pop-clo"><i class="fa fa-times" aria-hidden="true"></i></span>

    <div class="inn">
        <form name="new_chat_form" method="post" id="chatForm">
            <input type="hidden" name="receiver_id" id="receiver_id" value="">
            <div class="s1">
                <img src="" class="intephoto2" id="partner_photo" alt="">
                <h4><b class="intename2" id="partner_name"></b></h4>
                <span class="avlsta avilyes">Interested User</span>
            </div>
            <div class="s2 chat-box-messages" id="chatMessages">
                <div class="chat-loading">Loading messages...</div>
            </div>
            <div class="s3">
                <input type="text" name="chat_message" id="chat_message" placeholder="Type a message here.." required>
                <button id="chat_send" name="chat_send" type="submit">Send <i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
            </div>
        </form>
    </div>
</div>
<!-- END -->

<script src="<?php echo base_url('assets/frontend/'); ?>/assets/js/Chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var currentPartnerId = '';
    var currentPartnerName = '';

    // CHAT WINDOW OPEN
    $(".db-chat-trig").on('click', function () {
        currentPartnerId = $(this).data('partner-id');
        currentPartnerName = $(this).data('partner-name');
        
        // Set partner info in chat box
        $("#partner_name").text(currentPartnerName);
        $("#receiver_id").val(currentPartnerId);
        
        // Load partner photo
        var partnerPhoto = $(this).find('img').attr('src');
        $("#partner_photo").attr('src', partnerPhoto);
        
        // Load messages
        loadChatMessages(currentPartnerId);
        
        $(".chatbox").addClass("open");
    });

    // CLOSE CHAT WINDOW
    $(".comm-msg-pop-clo").on('click', function () {
        $(".chatbox").removeClass("open");
        currentPartnerId = '';
        currentPartnerName = '';
    });

    // SEND MESSAGE
    $("#chatForm").on('submit', function(e) {
        e.preventDefault();
        
        var message = $("#chat_message").val().trim();
        var receiverId = $("#receiver_id").val();
        
        if (message === '') {
            alert('Please enter a message');
            return;
        }
        
        if (receiverId === '') {
            alert('Please select a user to chat with');
            return;
        }
        
        sendMessage(receiverId, message);
    });

    // LOAD CHAT MESSAGES
    function loadChatMessages(partnerId) {
       /* $("#chatMessages").html('<div class="chat-loading">Loading messages...</div>');*/
        
        $.ajax({
            url: '<?php echo base_url("chat/getChat/"); ?>/' + partnerId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    displayMessages(response.messages);
                    updateUnreadCount();
                } else {
                    $("#chatMessages").html('<div class="chat-error">' + response.message + '</div>');
                }
            },
            error: function() {
                $("#chatMessages").html('<div class="chat-error">Error loading messages</div>');
            }
        });
    }

    // DISPLAY MESSAGES
    function displayMessages(messages) {
        var messagesHtml = '';
        var currentUserId = '<?php echo $usersession["user_id"]; ?>';
        
        if (messages.length === 0) {
            messagesHtml = '<span class="chat-wel">Start a new conversation with this interested user!</span>';
        } else {
            messages.forEach(function(message) {
                var messageClass = message.sender_id == currentUserId ? 'chat-rhs' : 'chat-lhs';
                var messageTime = new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                messagesHtml += '<div class="' + messageClass + '">' + 
                               '<div class="message-text">' + message.message + '</div>' +
                               '<div class="message-time">' + messageTime + '</div>' +
                               '</div>';
            });
        }
        
        $("#chatMessages").html(messagesHtml);
        // Scroll to bottom
        $("#chatMessages").scrollTop($("#chatMessages")[0].scrollHeight);
    }

    // SEND MESSAGE
    function sendMessage(receiverId, message) {
        $.ajax({
            url: '<?php echo base_url("chat/sendMessage"); ?>',
            type: 'POST',
            dataType: 'json',
            data: {
                receiver_id: receiverId,
                message: message
            },
            success: function(response) {
                if (response.status) {
                    $("#chat_message").val('');
                    loadChatMessages(receiverId);
                } else {
                    alert('Failed to send message: ' + response.message);
                }
            },
            error: function() {
                alert('Error sending message');
            }
        });
    }

    // UPDATE UNREAD COUNT
    function updateUnreadCount() {
        $.ajax({
            url: '<?php echo base_url("chat/getUnreadCount"); ?>',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    // You can update a global unread count badge here if needed
                    console.log('Unread messages:', response.unread_count);
                }
            }
        });
    }

    // AUTO REFRESH MESSAGES EVERY 5 SECONDS
    setInterval(function() {
        if (currentPartnerId !== '') {
            loadChatMessages(currentPartnerId);
        }
    }, 5000);
});
</script>

<style>
.chat-loading, .chat-error {
    text-align: center;
    padding: 20px;
    color: #666;
}

.chat-lhs {
    background: #f1f1f1;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    max-width: 70%;
    float: left;
    clear: both;
}

.chat-rhs {
    background: #007bff;
    color: white;
    padding: 10px;
    margin: 5px;
    border-radius: 10px;
    max-width: 70%;
    float: right;
    clear: both;
}

.chat-wel {
    text-align: center;
    display: block;
    padding: 20px;
    color: #666;
    font-style: italic;
}

.last-message {
    color: #666;
    font-size: 12px;
}

.message-time {
    font-size: 10px;
    text-align: right;
    margin-top: 5px;
    opacity: 0.7;
}

.no-chats {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>