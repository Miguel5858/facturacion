

 <!-- Bootstrap JavaScript Libraries -->
 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
    integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
  </script>

<script>


            // Mostrar el elemento de carga mientras la página se está cargando
            document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('loadingOverlay').style.display = 'flex';
            });

            // Ocultar el elemento de carga después de que la página se haya cargado completamente
            window.addEventListener("load", function() {
            setTimeout(function() {
                document.getElementById('loadingOverlay').style.display = 'none';
            }, 500); // Retraso de 2000 milisegundos (2 segundos)
            });
</script>

</body>

</html>


