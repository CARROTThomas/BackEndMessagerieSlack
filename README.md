<h1><strong>Projet Symfony : VoyageVue</strong></h1>

url : <a href="apislack.thomascarrot.com">apislack.thomascarrot.com</a>

Description du Projet :

Mise en place d'une api messagerie, 



<h2><strong>Documentation</strong></h2>

base url : apislack.thomascarrot.com

User

register : /register
login    : /api/login_check
logout   : 


Profil 
all_profille : /api/profile/all


Request 

send_request_friend     : /api/request/send/{profile_id}
get_all_yours_request   : /api/request/all
accept_one_your_request : /api/request/accept/{request_id}


Friend

get_friends_all : /api/friend/get


Conversation

private_conversation_create       : /api/private/conversation/create/{profile_id}
get_all_your_private_conversation : /api/private/conversation/all
get_all_messages_conversation     : /api/private/conversation/{private_conversation_id}/messages


Private Message

send_message   : /api/private/message/send/{private_conversation_id}
remove_message : /api/private/message/remove/{private_message_id}
edit_message   : /api/private/message/edit/{private_message_id}


Private Response

send_response   : /api/private/response/send/{private_message_id}
edit_response   : /api/private/response/edit/{private_message_id}
remove_response : /api/private/response/remove/{private_message_id}

Group Message
group_conversation_create           : /api/group/conversation/create
get_all_your_group_conversation     : /api/group/conversation/all
get_all_messages_group_conversation : /api/group/conversation/show/{group_conversation_id}
add_profile_group_conversation      : /api/group/conversation/people/add/{group_conversation_id}/{profile_id}
group_conversation_delete           : /api/group/conversation/delete/{group_conversation_id}


Group Message

send_message    : /api/group/conversation/send/{group_conversation_id}
edit_response   : /api/group/conversation/edit/{group_conversation_id}
remove_response : /api/group/conversation/remove/{group_conversation_id}


Images

upload_image_before_send_message : /api/image
response : 
{
    "status": "image upload",
    "imageId": 4,
    "imageUrl": "https://127.0.0.1:8000/media/cache/resolve/thumbnail/images/products/iconserver-65a4099f99cfa553628520.png"
}
