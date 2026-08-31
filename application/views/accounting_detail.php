<table class="table table-bordered">
<thead class='text-center'>
  <th width="60%">Accounting Entry</th>
  <th width="20%">Debit</th>
  <th width="20%">Credit</th>
  </thead>
  <tbody>
      
      <?php
    
        $total_debit=0;
        $total_credit=0;
    
      foreach($accounting->result() as $ind=>$acct){
            ?>
          <tr>
            <td><?=$acct->titlename?></td>
              <td class="text-right"><?=number_format($acct->debit,2)?></td>
              <td class="text-right"><?=number_format($acct->credit,2)?></td>
          </tr>
          <?php
                
            $total_debit += $acct->debit;    
            $total_credit += $acct->credit;    
                
        } ?>
      
  </tbody>
  <tfoot>
      <th class='text-right'>TOTAL (Php)</th>
      <th class='text-right'><?=number_format($total_debit,2)?></th>
      <th class='text-right'><?=number_format($total_credit,2)?></th>
  </tfoot>
</table>