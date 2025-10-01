<footer class="footer">
  <div class="container-fluid">
    <div class="d-block mx-auto">
    </div>
  </div>
</footer>

{{-- Botão Flutuante para Atendimento --}}
<a href="https://console.tomticket.com/dashboard/chat" target="_blank" class="btn-atendimento" title="Atendimento e Chamados">
  <i class="fas fa-headset"></i>
  <span class="btn-atendimento-text">Suporte</span>
</a>

<style>
  .btn-atendimento {
    position: fixed;
    bottom: 30px;
    right: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    z-index: 9999;
    transition: all 0.3s ease;
    text-decoration: none;
    overflow: hidden;
  }
  
  .btn-atendimento:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    color: white;
    width: 150px;
    border-radius: 30px;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    transform: translateY(-3px);
  }
  
  .btn-atendimento-text {
    display: none;
    margin-left: 10px;
    font-size: 14px;
    font-weight: 600;
    white-space: nowrap;
  }
  
  .btn-atendimento:hover .btn-atendimento-text {
    display: inline-block;
  }
  
  .btn-atendimento i {
    transition: all 0.3s ease;
  }
  
  .btn-atendimento:hover i {
    animation: shake 0.5s ease;
  }
  
  @keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    75% { transform: rotate(10deg); }
  }
  
  @media (max-width: 768px) {
    .btn-atendimento {
      bottom: 20px;
      right: 20px;
      width: 50px;
      height: 50px;
      font-size: 20px;
    }
  }
</style>
