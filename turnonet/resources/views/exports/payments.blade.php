<table style="width: 100%" width="100%">
   <thead>
      <tr>
         <th  width="20" style="width: 20px;"><strong>ID PAGO</strong></th>
         <th  width="30" style="width: 30px;"><strong>TURNO</strong></th>
         <th  width="30" style="width: 20px;"><strong>MONTO</strong></th>
         <th  width="20" style="width: 20px;"><strong>FECHA</strong></th>
         <th  width="40" style="width: 40px;"><strong>EMPRESA</strong></th>
         <th  width="40" style="width: 40px;"><strong>SUCURSAL</strong></th>
         <th  width="20" style="width: 20px;"><strong>PRESTADOR</strong></th>
         <th  width="50" style="width: 50px;"><strong>CLIENTE</strong></th>
         <th  width="40" style="width: 40px;"><strong>CORREO ELECTRÓNICO</strong></th>
        
      </tr>
   </thead>
   <tbody>
      @foreach($data as $rs)
      <tr>
         <td style="vertical-align: middle;">Cod: {{strval($rs['id_payment'])}}</td>
         <td style="vertical-align: middle;">Cod: {{strval($rs['code'])}}</td>
         <td style="vertical-align: middle;">$ {{ $rs['amount'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['created_at'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['business'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['branch'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['lender'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['name'] }}</td>
         <td style="vertical-align: middle;">{{ $rs['email'] }}</td>
        
      </tr>
      @endforeach
   </tbody>
</table>