var btn_crear_pqr = $('#crear_pqr');
var municipio = $('#municipio').val();
var departamento = $('#departamento').val();
var modal_pqr = $('#addPqr');
var btn_pqr = $('#btn-pqr');
var cliente_id = null;
var ticket = $('input[name=ticket]');

var cedula = $('#text-cedula');
var nombre = $('#text-nombre');
var correo_cliente = $('#text-correo');
var direccion = $('#text-direccion');
var telefono = $('#text-telefono');
var proyecto = $('#text-proyecto');
var estado = $('#text-estado');
var total_deuda = $('#text-total-deuda');
var link_cliente = $('#link-cliente');

var alerta_ticket = $('#alerta-ticket');
var mantenimiento = $('input[name=mantenimiento]');
var cun = $('input[name=cun]');

var jornada = null;
var fecha_limite = null;
var celular = null;
var correo = null;

var btn_solicitud = $('#btn-solicitud');
var btn_add_solicitud = $('#btn-add-solicitud');
var btn_enviar = $('#btn_enviar');

const fecha_hoy = new Date();
