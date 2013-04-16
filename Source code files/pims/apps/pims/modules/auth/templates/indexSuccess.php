<h1>Users List</h1>

<table>
  <thead>
    <tr>
      <th>User</th>
      <th>User name</th>
      <th>User password</th>
      <th>Is admin</th>
      <th>Status</th>
      <th>User group</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($users as $user): ?>
    <tr>
      <td><a href="<?php echo url_for('auth/show?user_id='.$user->getUserId()) ?>"><?php echo $user->getUserId() ?></a></td>
      <td><?php echo $user->getUserName() ?></td>
      <td><?php echo $user->getUserPassword() ?></td>
      <td><?php echo $user->getIsAdmin() ?></td>
      <td><?php echo $user->getStatus() ?></td>
      <td><?php echo $user->getUserGroupId() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('auth/new') ?>">New</a>
