<h1>Reports List</h1>

<table>
  <thead>
    <tr>
      <th>Id</th>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($reports as $report): ?>
    <tr>
      <td><a href="<?php echo url_for('report/show?id='.$report->getId()) ?>"><?php echo $report->getId() ?></a></td>
      <td><?php echo $report->getName() ?></td>
      <td><?php echo $report->getDescription() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('report/new') ?>">New</a>
