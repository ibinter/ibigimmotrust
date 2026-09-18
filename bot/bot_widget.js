(function(){

  const btn = document.createElement('div');
  btn.id = 'ibig-bot-btn';
  btn.innerHTML = '💬';

  Object.assign(btn.style, {
    position:'fixed',
    bottom:'20px',
    right:'20px',
    width:'60px',
    height:'60px',
    borderRadius:'50%',
    background:'#003c96',
    color:'#fff',
    display:'flex',
    alignItems:'center',
    justifyContent:'center',
    fontSize:'28px',
    cursor:'pointer',
    zIndex:'999999',
    boxShadow:'0 8px 20px rgba(0,0,0,0.25)'
  });

  document.addEventListener('DOMContentLoaded', () => {
    document.body.appendChild(btn);

    const frame = document.createElement('iframe');
    frame.src = '/bot/chatbox.php';

    Object.assign(frame.style, {
      position:'fixed',
      bottom:'90px',
      right:'20px',
      width:'380px',
      height:'560px',
      border:'none',
      borderRadius:'16px',
      boxShadow:'0 12px 40px rgba(0,0,0,0.35)',
      display:'none',
      zIndex:'999999'
    });

    document.body.appendChild(frame);

    btn.onclick = () => {
      frame.style.display = frame.style.display === 'none' ? 'block':'none';
    };
  });

})();
