<style>
  /* 3D Floating UI Styles */
  .hero-3d-wrapper {
    perspective: 1200px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 500px;
    position: relative;
    z-index: 10;
  }

  .hero-3d-container {
    position: relative;
    width: 460px;
    height: 460px;
    transform-style: preserve-3d;
    transform: rotateX(15deg) rotateY(-15deg);
    transition: transform 0.1s ease-out;
  }
  @media (max-width: 640px) {
    .hero-3d-container {
      transform: scale(0.7) rotateX(15deg) rotateY(-15deg);
    }
  }

  /* Base Card Style (Glassmorphism) */
  .float-card {
    position: absolute;
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: 20px;
    box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255,255,255,0.2);
    transform-style: preserve-3d;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  }

  .float-card:hover {
    box-shadow: 0 40px 80px rgba(0, 212, 255, 0.25), inset 0 1px 0 rgba(255,255,255,0.3);
    border-color: rgba(0, 212, 255, 0.3);
  }

  /* Animations for floating */
  @keyframes float1 { 0%, 100% { transform: translateY(0) translateZ(40px); } 50% { transform: translateY(-15px) translateZ(40px); } }
  @keyframes float2 { 0%, 100% { transform: translateY(0) translateZ(100px); } 50% { transform: translateY(-10px) translateZ(100px); } }
  @keyframes float3 { 0%, 100% { transform: translateY(0) translateZ(120px); } 50% { transform: translateY(-12px) translateZ(120px); } }
  @keyframes float4 { 0%, 100% { transform: translateY(0) translateZ(150px) rotateZ(5deg); } 50% { transform: translateY(-8px) translateZ(150px) rotateZ(5deg); } }
  @keyframes float5 { 0%, 100% { transform: translateY(0) translateZ(80px); } 50% { transform: translateY(-18px) translateZ(80px); } }

  /* 1. Main Card */
  .card-main {
    width: 320px;
    height: 420px;
    left: 70px;
    top: 20px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.75), rgba(15, 23, 42, 0.85));
    border: 1px solid rgba(0, 212, 255, 0.25);
    box-shadow: 0 35px 70px rgba(0,0,0,0.6), 0 0 30px rgba(0, 212, 255, 0.15);
    animation: float1 6s infinite ease-in-out;
  }

  .main-img {
    width: 100%;
    height: 160px;
    border-radius: 14px;
    background: url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop') center/cover;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255,255,255,0.1);
  }
  .main-img::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(15, 23, 42, 1) 0%, transparent 80%);
  }
  
  .game-title {
    font-size: 22px; font-weight: 800; color: #fff; margin: 16px 0 4px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    font-family: 'Oxanium', sans-serif;
  }
  .game-subtitle {
    font-size: 13px; color: #94a3b8; margin-bottom: 20px; font-weight: 600;
  }
  
  .topup-btn {
    width: 100%; padding: 12px;
    background: linear-gradient(90deg, #00D4FF, #8B5CF6);
    border: none; border-radius: 12px;
    color: #fff; font-weight: 700; font-size: 15px;
    cursor: pointer; font-family: 'Oxanium', sans-serif;
    box-shadow: 0 10px 25px rgba(139, 92, 246, 0.4);
    transition: all 0.3s ease;
    display: flex; justify-content: center; align-items: center; gap: 8px;
  }
  .topup-btn:hover {
    transform: scale(1.03) translateY(-2px);
    box-shadow: 0 15px 35px rgba(0, 212, 255, 0.5);
  }

  /* 2. Payment Confirmed Card */
  .card-payment {
    width: 230px;
    height: 75px;
    left: -20px;
    top: 70px;
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.5);
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 14px;
    animation: float2 5s infinite ease-in-out 0.5s;
  }
  .icon-success {
    width: 38px; height: 38px;
    background: linear-gradient(135deg, #10B981, #059669);
    border-radius: 50%;
    display: flex; justify-content: center; align-items: center;
    color: white; font-size: 20px;
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.6);
  }
  .payment-text h4 { margin: 0; color: #fff; font-size: 14px; text-shadow: 0 1px 2px rgba(0,0,0,0.5); font-family: 'Oxanium', sans-serif;}
  .payment-text p { margin: 2px 0 0; color: #a7f3d0; font-size: 12px; font-weight: 600; }

  /* 3. Server Select */
  .card-server {
    width: 150px;
    height: 60px;
    right: -10px;
    top: 220px;
    background: rgba(30, 41, 59, 0.85);
    display: flex;
    align-items: center;
    padding: 0 16px;
    justify-content: space-between;
    animation: float3 4.5s infinite ease-in-out 1s;
  }
  .server-info { display: flex; flex-direction: column; }
  .server-info span:first-child { font-size: 11px; color: #94a3b8; }
  .server-info span:last-child { font-size: 13px; color: #fff; font-weight: 700; }
  .server-arrow { color: #00D4FF; font-size: 10px;}

  /* 4. Promo Badge */
  .card-promo {
    width: max-content;
    padding: 8px 16px;
    right: 20px;
    top: -10px;
    background: linear-gradient(90deg, #FFD700, #F59E0B);
    border: 1px solid #FEF3C7;
    border-radius: 30px;
    color: #713F12;
    font-weight: 800;
    font-size: 13px;
    letter-spacing: 1px;
    box-shadow: 0 12px 25px rgba(245, 158, 11, 0.5);
    animation: float4 5.5s infinite ease-in-out 0.2s;
    font-family: 'Oxanium', sans-serif;
  }

  /* 5. User Receipt */
  .card-receipt {
    width: 250px;
    height: 65px;
    left: 20px;
    bottom: -10px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    gap: 12px;
    border: 1px solid rgba(139, 92, 246, 0.4);
    background: rgba(15, 23, 42, 0.7);
    animation: float5 6.5s infinite ease-in-out 0.8s;
  }
  .avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: url('https://i.pravatar.cc/150?u=gaming') center/cover;
    border: 2px solid #8B5CF6;
    box-shadow: 0 0 10px rgba(139, 92, 246, 0.5);
  }
  .receipt-text h4 { margin: 0; color: #fff; font-size: 13px; font-family: 'Oxanium', sans-serif;}
  .receipt-text p { margin: 2px 0 0; color: #cbd5e1; font-size: 11px; }
  .bolt { color: #FFD700; text-shadow: 0 0 5px rgba(255, 215, 0, 0.5); }

  /* Diamonds/Coins styling inside main card */
  .denom-grid {
    display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px;
  }
  .denom-item {
    background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
    border-radius: 10px; padding: 10px; text-align: center;
    cursor: pointer; transition: 0.2s;
  }
  .denom-item:hover, .denom-item.active {
    background: rgba(0, 212, 255, 0.15); border-color: #00D4FF;
    box-shadow: 0 0 15px rgba(0, 212, 255, 0.2);
  }
  .denom-val { font-weight: 800; color: #fff; font-size: 15px; display: flex; justify-content: center; align-items: center; gap: 6px; }
  .denom-price { font-size: 12px; color: #94a3b8; margin-top: 4px; font-weight: 600; }
  
  .diamond-icon { width: 16px; height: 16px; fill: #00D4FF; filter: drop-shadow(0 0 2px rgba(0, 212, 255, 0.8)); }
</style>

<div class="hero-3d-wrapper relative z-10 hidden lg:flex">
  <div class="hero-3d-container" id="model-container">
    
    <!-- 1. Main Card -->
    <div class="float-card card-main">
      <div class="main-img"></div>
      <div class="game-title">Cosmic Warfare</div>
      <div class="game-subtitle">User ID: 8847291 • Zone: Global</div>
      
      <div class="denom-grid">
        <div class="denom-item active">
          <div class="denom-val">
            <svg class="diamond-icon" viewBox="0 0 24 24"><path d="M12 2L2 12l10 10 10-10L12 2zm0 3.5l6.5 6.5-6.5 6.5-6.5-6.5L12 5.5z"/></svg>
            250
          </div>
          <div class="denom-price">Rp 45.000</div>
        </div>
        <div class="denom-item">
          <div class="denom-val">
            <svg class="diamond-icon" viewBox="0 0 24 24"><path d="M12 2L2 12l10 10 10-10L12 2zm0 3.5l6.5 6.5-6.5 6.5-6.5-6.5L12 5.5z"/></svg>
            750
          </div>
          <div class="denom-price">Rp 120.000</div>
        </div>
      </div>

      <button class="topup-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
        Top Up Now
      </button>
    </div>

    <!-- 2. Payment Confirmed -->
    <div class="float-card card-payment">
      <div class="icon-success">✓</div>
      <div class="payment-text">
        <h4>Payment Confirmed</h4>
        <p>250 Diamonds - Rp 45.000</p>
      </div>
    </div>

    <!-- 3. Server Select -->
    <div class="float-card card-server">
      <div class="server-info">
        <span>Region</span>
        <span>SEA Server</span>
      </div>
      <div class="server-arrow">▼</div>
    </div>

    <!-- 4. Promo Badge -->
    <div class="float-card card-promo">
      BONUS +20% TODAY
    </div>

    <!-- 5. User Receipt -->
    <div class="float-card card-receipt">
      <div class="avatar"></div>
      <div class="receipt-text">
        <h4>Order #92841 <span class="bolt">⚡</span></h4>
        <p>Delivered instantly in 2s</p>
      </div>
    </div>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('model-container');
    if(!container) return;
    
    let targetX = 15;
    let targetY = -15;
    let currentX = 15;
    let currentY = -15;

    const lerp = (start, end, factor) => start + (end - start) * factor;

    const animate = () => {
      if(window.innerWidth >= 1024) {
        currentX = lerp(currentX, targetX, 0.06);
        currentY = lerp(currentY, targetY, 0.06);
        container.style.transform = `rotateX(${currentX}deg) rotateY(${currentY}deg)`;
      } else {
        container.style.transform = `scale(0.7) rotateX(15deg) rotateY(-15deg)`;
      }
      requestAnimationFrame(animate);
    };
    
    animate();

    document.addEventListener('mousemove', (e) => {
      if(window.innerWidth < 1024) return;
      // Calculate rotation based on cursor position relative to center of screen
      const xRot = ((window.innerHeight / 2) - e.pageY) / 35;
      const yRot = (e.pageX - (window.innerWidth / 2)) / 35;
      
      targetX = xRot + 15;
      targetY = yRot - 15;
    });

    document.addEventListener('mouseleave', () => {
      targetX = 15;
      targetY = -15;
    });
  });
</script>
