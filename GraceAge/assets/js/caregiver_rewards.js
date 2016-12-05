window.onload = showRewards;
var $rewards_list = $('#rewards_list');

function showRewards(){ 
    $.post("get_rewards", function(rewards){  
        $.each(rewards, function(i, reward){            
            $rewards_list.append("<li id='" + reward.Id +"' onClick='rewardClick(this.id)'>"+ "Reward: "+reward.Reward + " Price: "+ reward.Price +" Language: "+ reward.Language+ "</li>"); 
        });
    });
}

function add_new_reward(){
    var new_reward = $("#new_reward").val();
    $.post("add_tip", {reward: new_reward}, function(){
        $rewards_list.append("<li>"+ new_reward +"</li>");
        alert("yes...");
    });
}


   function rewardClick(id){ // do something when a tip is clicked
       
       $(document.getElementById('editform')).remove(); // remove old form if it excists
           
      $(document.getElementById(id)).after("<form id='editform' ></form>"); // show a form here to update or delete the question
      $(document.getElementById('editform')).append("<input type='text' id='newtext'>");
      $(document.getElementById('editform')).append("<input type='button' onclick='updateReward(" + id+ ")' value='update'>"); // button run updateTip(id) on klick
      
      
     
};
