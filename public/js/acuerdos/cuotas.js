
function generar(){
    var cuotas = document.getElementById('cuotas');
    var fecha_inicial = document.getElementById('dia_pagar');
    var cuota_inicial = document.getElementById('valor_inicial');
    var valor_per = document.getElementById('descontado');
    var tarifa = document.getElementById('text-tarifa-internet');
    var inicial = parseFloat(cuota_inicial.value);
    var valor_perdonar = parseFloat(valor_per.value);
    var deuda = parseFloat($('#deuda').val());
    var tarifaIn = tarifa.innerText.replace(/\$|\./g, "");
    tarifaIn = parseFloat(tarifaIn.replace(/,.*$/, ""));
    var valor = cuotas.value;
    
    if(inicial == 0){
       inicial = (deuda - valor_perdonar) / valor;
    }

    $('#t_cuotas').empty();

    if(deuda != ''){        
        if(inicial <= deuda){


            if(valor_perdonar < deuda){               

                var fecha_inicio = fecha_inicial.value;
                f_actual = new Date();

                if(new Date(fecha_inicio) >= f_actual){
                    if(fecha_inicio != '' && valor != '' && valor_per.value != ''){ 
                        for (var i = 1; i <= valor ; i++){
    
                            $("#informcaion_registrar tbody").append(`<tr class="fila-par">
                                <td>
                                    <p id="numero_cuota">${i}</p>
                                </td>
                                <td>
                                    <input type="date" class="form-control" name="fecha_pago[]" id="fecha_pago${i}" readonly>
                                </td>
                                <td id="valor_tarifa${i}"></td>
                                <td id="valor_cuota${i}"></td>
                                <td>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-dollar"></i></span>
                                        <input type="text" class="form-control" name="valor_pagar[]" id="valor_pagar${i}" readonly>
                                    </div>
                                </td>
                            </tr>`);
                        }
                                
                        $('#fecha_pago1').val(fecha_inicio);
                       
                        var date = new Date(fecha_inicio);
                        var mes = date.getMonth();
                        var dia = date.getDate()+1;
    
                        //+formulas
                        var fecha_actual = new Date();
                        var mes_inicio = date.getMonth()+1;
                        var mesActual = fecha_actual.getMonth() + 1;

                        if(valor == 1){
                            var meses_diff = mes_inicio - mesActual;
                            if(meses_diff > 0){
                                var servicio_su = tarifaIn*meses_diff;
                                servicio_su = servicio_su + deuda - valor_perdonar;
                               
                                $('#valor_pagar1').val(servicio_su);
    
                                var cuota_op = servicio_su - tarifaIn;
                                $('#valor_cuota1').text(formato_dinero(cuota_op));
                                $('#valor_tarifa1').text(formato_dinero(tarifaIn));
                                $('#crear_acuerdo').attr('disabled',false);
    
                            }else{
                                
                                inicial = inicial - valor_perdonar;
                                $('#valor_pagar1').val(inicial);
                                $('#valor_tarifa1').text('$0');
                                $('#valor_cuota1').text(formato_dinero(inicial));
                                $('#crear_acuerdo').attr('disabled',false);
                            }

                        }else{

                            if(inicial != ''){
                                var tarifa_s_t = 0
                                if(mes_inicio > mesActual){              
                                    $('#valor_tarifa1').text(formato_dinero(tarifaIn));
                                    tarifa_s_t = tarifaIn;
                                }else{
                                    $('#valor_tarifa1').text('$0');
                                }
                                $('#valor_pagar1').val((inicial+ tarifa_s_t).toFixed(2));
                                $('#valor_cuota1').text(formato_dinero(inicial));

                            }
                        }
    
    
                        if(valor > 1){ 
                            var cuotas_mult = 0;                           
                            deuda = deuda-inicial-valor_perdonar;                           

                            
                            cuotas_mult = valor-1;
                            
                            deudaTRF = tarifaIn * cuotas_mult;
                            if(deuda > 0){
                                deuda = deuda + deudaTRF;
                                cuotas = valor-1;               
                                deuda = deuda / cuotas; 
                                couta = deuda;
                                for (var i = 2; i <= valor ; i++){
                                    mes = mes + 1;
                                    date.setMonth(mes,dia);
                                    $('#fecha_pago'+i).val(date.toLocaleDateString('fr-CA'));
                                    $('#valor_tarifa'+i).text(tarifa.innerText); 
                                    var cuota_op = couta - tarifaIn;      
                                    $('#valor_cuota'+i).text(formato_dinero(cuota_op));
                                    $('#valor_pagar'+i).val(couta.toFixed(2));
                                }

                                $('#crear_acuerdo').attr('disabled',false);

                            }else{
                                toastr.options.positionClass = 'toast-bottom-right';
                                toastr.warning('Las cuotas no tienen un valor!');
                                $('#crear_acuerdo').attr('disabled',true)

                                for (var i = 1; i <= valor ; i++){
                                    mes = mes + 1;
                                    date.setMonth(mes,dia);
                                    $('#fecha_pago'+i).val(date.toLocaleDateString('fr-CA'));
                                    $('#valor_tarifa'+i).text('$0'); 
                                    $('#valor_cuota'+i).text('$0');
                                    $('#valor_pagar'+i).val('$0');
                                }

                            }
                           
                        }
    
                    }else{
                        toastr.options.positionClass = 'toast-bottom-right';
                        toastr.warning('Llenar los campos para generar las cuotas!');
                        $('#crear_acuerdo').attr('disabled',true)

                    }
                }else{
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning('La fecha inicial no es valida!');
    
                    $('#crear_acuerdo').attr('disabled',true)
                } 

            }else{
                if(valor_perdonar == deuda){
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning('No es posible perdonar la deuda completa!');
                }else{              
                    toastr.options.positionClass = 'toast-bottom-right';
                    toastr.warning('El valor a perdonar no puede ser mayor a la deuda!');
                }
                $('#crear_acuerdo').attr('disabled',true)
            }    
            
        }else{
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.warning('El valor inicial no puede ser mayor a la deuda!');

            $('#crear_acuerdo').attr('disabled',true)

        }
    }else{
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.warning('Consultar un cliente!');

        $('#crear_acuerdo').attr('disabled',true)

    }
      
};

   