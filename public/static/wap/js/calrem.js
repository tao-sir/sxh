maxWidth();

window.onresize=function(){    
    maxWidth();
}  
//最大1000
function maxWidth(){
    var html = document.getElementsByTagName("html")[0],
        windowWidth = document.body.clientWidth;
    html.style.margin="0 auto";
    if(windowWidth>1000){
        html.style.width="1000px";     
        html.style.fontSize="100px";
    }else{
        html.style.width=windowWidth+"px";
        html.style.fontSize=windowWidth/10+"px";
    }
}