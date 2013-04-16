<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $report->getId() ?></td>
    </tr>
    <tr>
      <th>Name:</th>
      <td><?php echo $report->getName() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $report->getDescription() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('report/edit?id='.$report->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('report/index') ?>">List</a>
