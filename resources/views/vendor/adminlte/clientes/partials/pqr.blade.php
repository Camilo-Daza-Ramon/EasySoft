<div class="col-md-12">
  <div class="panel panel-default"> 
    <div class="panel-heading">
      <h2><i class="fa fa-comments-o"></i>  PQR
      </h2> 
    </div>
    <div class="panel-body table-responsive">
      <table class="table table-hover">
        <thead>
          <tr>
            <th style="width: 10px">#</th>
            <th>CUN</th>
            <th>Fecha inicio</th>
            <th>Fecha limite</th>
            <th>Fecha de cierre</th>
            <th>Tipo</th>
            <th>Estado</th>
            <th>Total Días</th>
          </tr>
        </thead>
        <tbody>
          <?php $k = 0; ?>
        @foreach($cliente->pqr as $pqr)          
          <tr>
            <td>{{$k+=1}}</td>
            <td>
              <a href="{{route('pqr.show', $pqr->PqrId)}}" target="_black">
                {{$pqr->CUN}}
              </a>              
            </td>
            <td>
              @if(!empty($pqr->FechaApertura))
              {{date('Y-m-d h:i:s', strtotime($pqr->FechaApertura))}}
              @endif
            </td>
            <td>
              @if(!empty($pqr->FechaMaxima))
              {{date('Y-m-d h:i:s', strtotime($pqr->FechaMaxima))}}
              @endif
            </td>
            <td>
              @if(!empty($pqr->FechaCierre))
              {{date('Y-m-d h:i:s', strtotime($pqr->FechaCierre))}}
              @endif
            </td>
            <td>{{$pqr->TipoSolicitud}}</td>           
            
            <td>
              @if($pqr->Status == 'ABIERTO')
                <span class="label label-success">{{$pqr->Status}}</span>
              @elseif($pqr->Status == 'CERRADO')
                <span class="label label-default">{{$pqr->Status}}</span>
              @else
                <span class="label label-warning">{{$pqr->Status}}</span>
              @endif
            </td>
            <td>
              <?php 
              $contador = date_diff(date_create($pqr->FechaApertura), date_create($pqr->FechaCierre));
              ?>
              {{$contador->format('%a')}} Días sin solución
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>    
  </div>
</div>

