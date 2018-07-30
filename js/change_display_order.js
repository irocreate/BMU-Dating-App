/* Change Order Display Script - AB9 */
jQuery(document).ready(function($){
   //$('.slide_me_up').hide();
   //$('.slide_me_down').hide();
});

function dsp_hide_selected(parent_up_level)
{
    var i;
    var parent='';
    for(i=0;i<parent_up_level;i++)
    {
        parent = parent + '.parent()';
    }
    
    var slide_up = 'jQuery("#slide_me_up")'+parent;
    var slide_down = 'jQuery("#slide_me_down")'+parent;
    
    setTimeout(function(){console.log(eval(slide_up));console.log(eval(slide_down));dsp_make_me_hidden(slide_up,slide_down);},50);   
}

function dsp_change_display_order_animation(parent_up_level)
{
    var i;
    var parent='';
    var blink_speed = 500; // 0.5 sec
    var interval_speed = 1000; // 1 sec
    var blink_duration = 5000; // 4 sec
    var slide_down_duration = 1500;
    
    for(i=0;i<parent_up_level;i++)
    {
        parent = parent + '.parent()';
    }
    
    var slide_up = 'jQuery("#slide_me_up")'+parent;
    var slide_down = 'jQuery("#slide_me_down")'+parent;
    
    //dsp_make_me_hidden(slide_up,slide_down);
    
    jQuery('html, body').animate({
        scrollTop: eval(slide_up).prev().offset().top
    }, slide_down_duration);
    
    dsp_make_me_visible(slide_up,slide_down);
    dsp_swap_animation(slide_up,slide_down);
    
    // for blinking
    /*
    var setInter = setInterval( function(){
        
        //dsp_show_me_color(slide_up,slide_down);
        //setTimeout(function(){ dsp_hide_me_color(slide_up,slide_down); }, blink_speed  );
    },interval_speed);
    
    setTimeout(function(){
        clearInterval(setInter);
        //dsp_hide_me_color(slide_up,slide_down);
    },blink_duration);
    */   
}

function dsp_hide_me_color(slide_up,slide_down)
{
    eval(slide_up).css('background','');
    eval(slide_down).css('background','');
}

function dsp_show_me_color(slide_up,slide_down)
{
    var background_color1 = 'wheat';
    var background_color2 = 'wheat';
    eval(slide_up).css('background',background_color1);
    eval(slide_down).css('background',background_color2);
}

function dsp_make_me_hidden(slide_up,slide_down)
{
    //eval(slide_up).css('opacity','0');
    //eval(slide_down).css('opacity','0');
    eval(slide_up).css('visibility','hidden');
    eval(slide_down).css('visibility','hidden');
    eval(slide_up).css('position','relative');
    eval(slide_down).css('position','relative');
}

function dsp_make_me_visible(slide_up,slide_down)
{
    eval(slide_up).css('visibility','visible');
    eval(slide_down).css('visibility','visible');
}

function dsp_swap_animation(slide_up,slide_down)
{
    //eval(slide_up).css('visibility','visible');
    //eval(slide_down).css('visibility','visible');
    
    var pos1 = eval(slide_up).offset().top;
    var pos2 = eval(slide_down).offset().top;
    
    eval(slide_up).offset({top : pos2 });
    eval(slide_down).offset({top : pos1 });
    
    var diff = pos1 - pos2 + 'px';
    
    setTimeout(function(){
        //dsp_show_me_color(slide_up,slide_down);
        eval(slide_up).animate({
            top: "+=" + diff,
            //opacity: '1',
        }, 2500);
        eval(slide_down).animate({
            top: "-=" + diff,
            //opacity: '1',
        }, 2500);     
    },1500);   
    
    //setTimeout(function(){ dsp_hide_me_color(slide_up,slide_down); },3500);
    
    var current_url = window.location.href;
    var remove_from = current_url.indexOf('slide_up') - 1;
    current_url = current_url.substring(0,remove_from);
    window.history.pushState("string", "Title", current_url);
}