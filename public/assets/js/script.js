document.addEventListener('DOMContentLoaded', function () {
  // Konfirmasi sebelum menghapus data
  document.querySelectorAll('.rk-confirm-delete').forEach(function (el) {
    el.addEventListener('click', function (e) {
      var pesan = el.getAttribute('data-confirm') || 'Yakin ingin menghapus data ini?';
      if (!window.confirm(pesan)) {
        e.preventDefault();
      }
    });
  });

  // Auto-hilang alert sukses/error setelah beberapa detik
  document.querySelectorAll('.rk-alert-auto').forEach(function (el) {
    setTimeout(function () {
      el.classList.add('fade');
      el.style.opacity = '0';
      setTimeout(function () {
        el.remove();
      }, 300);
    }, 4000);
  });

  // Preview foto sebelum upload (form tambah/edit kendaraan)
  var inputFoto = document.querySelector('#input-foto');
  var previewFoto = document.querySelector('#preview-foto');
  if (inputFoto && previewFoto) {
    inputFoto.addEventListener('change', function () {
      var file = inputFoto.files && inputFoto.files[0];
      if (file) {
        previewFoto.src = URL.createObjectURL(file);
        previewFoto.classList.remove('d-none');
      }
    });
  }
});
