(function(){
  const btn = document.createElement('div');
  btn.style.position='fixed';
  btn.style.bottom='20px';
  btn.style.right='20px';
  btn.style.width='54px';
  btn.style.height='54px';
  btn.style.borderRadius='50%';
  btn.style.background='#003c96';
  btn.style.color='#fff';
  btn.style.display='flex';
  btn.style.alignItems='center';
  btn.style.justifyContent='center';
  btn.style.boxShadow='0 8px 20px rgba(0,0,0,0.25)';
  btn.style.cursor='pointer';
  btn.style.zIndex='9999';
  btn.innerText='💬';
  document.body.appendChild(btn);

  const frame = document.createElement('iframe');
  frame.src='/bot/ibig_bot.php';
  frame.style.position='fixed';
  frame.style.bottom='80px';
  frame.style.right='20px';
  frame.style.width='360px';
  frame.style.height='480px';
  frame.style.border='none';
  frame.style.borderRadius='16px';
  frame.style.boxShadow='0 10px 30px rgba(0,0,0,0.3)';
  frame.style.zIndex='9999';
  frame.style.display='none';
  document.body.appendChild(frame);

  btn.addEventListener('click',()=>{
    frame.style.display = (frame.style.display==='none'?'block':'none');
  });
})();
