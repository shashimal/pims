<h1>User groups List</h1>

<table>
  <thead>
    <tr>
      <th>User group</th>
      <th>User group name</th>
      <th>Description</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($user_groups as $user_group): ?>
    <tr>
      <td><a href="<?php echo url_for('admin/show?user_group_id='.$user_group->getUserGroupId()) ?>"><?php echo $user_group->getUserGroupId() ?></a></td>
      <td><?php echo $user_group->getUserGroupName() ?></td>
      <td><?php echo $user_group->getDescription() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('admin/new') ?>">New</a>
