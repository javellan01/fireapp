// Central de funções JScript Javell_2018

		function require(file,callback){
            var head=document.getElementsByTagName("head")[0];
            var script=document.createElement('script');
            script.src=file;
            script.type='text/javascript';
            //real browsers
            script.onload=callback;
            //Internet explorer
            script.onreadystatechange = function() {
                if (_this.readyState == 'complete') {
                    callback();
                }
            }
            head.appendChild(script);
        }
		function loadPhp(str) {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("main").innerHTML = this.responseText;
			
			$('#modalPedido').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget);
			  var cliente = button.data('cliente');
			  var id_cliente = button.data('id_cliente');
			  var modal = $(this);
			  modal.find('#formSCliente.form-control').val(cliente);
			  modal.find('#formidCliente.form-control').val(id_cliente);
			});
			
			$('#modalEdUsr').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget);
			  var id_usuario = button.data('uid');
			  var catuser = button.data('catuser');
			  var uid = document.getElementById('uid'+id_usuario);
			  
			  var nome = uid.getElementsByClassName('uname');
			  var tel = uid.getElementsByClassName('utel');
			  var mail = uid.getElementsByClassName('umail');
			  var cpf = uid.getElementsByClassName('ucpf');

			  var modal = $(this);
			  
			  modal.find('#formUser.form-control').val(nome[0].innerHTML);
			  modal.find('#formUserid.form-control').val(id_usuario);
			  modal.find('#formCPF.form-control').val(cpf[0].innerHTML);
			  modal.find('#formEmail.form-control').val(mail[0].innerHTML);
			  modal.find('#formTel.form-control').val(tel[0].innerHTML);
			  document.getElementById('formECatuser').options.selectIndex = catuser;
			  console.log(catuser);
			  console.log(document.getElementById('formECatuser').options.selectIndex);
			 
			});
			
			$('#formCNPJ').mask('00.000.000/0000-00', {reverse: false});
			$('#formCPF').mask('000.000.000-00', {reverse: false});
			$('#formTel').mask('(00) #0000-0000', {reverse: false});
			$('#formData').mask('00/00/0000', {reverse: false});
			$('#formDataA').mask('00/00/0000', {reverse: false});
			$('#formDataB').mask('00/00/0000', {reverse: false});
			
			$('.modal').on('hide.bs.modal', function (){
				loadPhp(str);
			});
			
			}		
				
			
			};
		
		xhttp.open("GET", str, true);
		xhttp.send();
			}
			
		function atvPhp(str) {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("main").innerHTML = this.responseText;
			
			$('.modal').on('hide.bs.modal', function (){
				atvPhp(str);
			});
			
			$('#modalUpdate').on('show.bs.modal', function (event) {
			  var button = $(event.relatedTarget);
			  var atividade = button.data('atividade');
			  var id_atividade = button.data('id_atividade');
			  var modal = $(this);
			  modal.find('.modal-title').text(atividade);
			  modal.find('#formAid.form-control').val(id_atividade);
			});
			
			}
			
			};
			xhttp.open("GET", "atividades.php?pid="+str, true);
			xhttp.send();
			}
			
		function download(str) {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("main").innerHTML = this.responseText;
			
			}
			
			};
			xhttp.open("GET", "download.php?"+str, true);
			xhttp.send();
			}
			
		function load_calendar(str) {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("calendar").innerHTML = this.responseText;
			
			$(document).ready(function() {

			$('#calendar').fullCalendar({
			  defaultDate: str,
			  editable: true,
			  eventLimit: true,
			  events: {
				url:'load_events.php',
				error: function() {
				  $('#script-warning').show();
				}
			  }
			});

			});
				}
				
			};
			xhttp.open("GET","calendario.php");
			xhttp.send();
			}
		//Processa Medicao
		function formMProc() {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("process").innerHTML = this.responseText;
			}
			};
			
			var formData = $('form.medicao').serialize();
			
			toastr.info(formData);
			toastr.options.progressBar = true;
			
			xhttp.open("GET", "mprocess.php?"+formData, true);
			xhttp.send();
			}
		//Processo Base do Sistema	
			function formProc() {
			var xhttp = new XMLHttpRequest();
				xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
			document.getElementById("process").innerHTML = this.responseText;
			}
			};
			
			var formData = $('form').serialize();

			toastr.info(formData);
			toastr.options.progressBar = true;
			
			xhttp.open("GET", "process.php?"+formData, true);
			xhttp.send();
			}
			
			
			
