<style>
.footer {
  position: fixed;
  bottom: 0;
  left: 250px; /* default sidebar width */
  width: calc(100% - 250px);
  background: #fff;
  padding: 10px 0;
  text-align: center;   /* ✅ teks rata tengah */
  font-size: 14px;
  color: #555;
  z-index: 1030;
  border-top: none;     /* ✅ biar gak kelihatan garis */
}

/* Kalau sidebar di-minimize */
.sidebar-minimized .footer {
  left: 70px;
  width: calc(100% - 70px);
}
</style>

<footer class="footer" >
  <div class="copyright">
    Dibuat  Oleh
    <i href="http://www.themekita.com">Guzhao</i>
    <i class="fa fa-heart text-danger"></i>
  </div>
</footer>
