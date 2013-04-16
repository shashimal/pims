<table>
  <tbody>
    <tr>
      <th>User:</th>
      <td><?php echo $user->getUserId() ?></td>
    </tr>
    <tr>
      <th>User name:</th>
      <td><?php echo $user->getUserName() ?></td>
    </tr>
    <tr>
      <th>User password:</th>
      <td><?php echo $user->getUserPassword() ?></td>
    </tr>
    <tr>
      <th>Is admin:</th>
      <td><?php echo $user->getIsAdmin() ?></td>
    </tr>
    <tr>
      <th>Status:</th>
      <td><?php echo $user->getStatus() ?></td>
    </tr>
    <tr>
      <th>User group:</th>
      <td><?php echo $user->getUserGroupId() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('auth/edit?user_id='.$user->getUserId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('auth/index') ?>">List</a>
