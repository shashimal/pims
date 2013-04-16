<h1>Episodes List</h1>

<table>
  <thead>
    <tr>
      <th>Episode no</th>
      <th>Patient no</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($episodes as $episode): ?>
    <tr>
      <td><a href="<?php echo url_for('consultancy/show?episode_no='.$episode->getEpisodeNo().'&patient_no='.$episode->getPatientNo()) ?>"><?php echo $episode->getEpisodeNo() ?></a></td>
      <td><a href="<?php echo url_for('consultancy/show?episode_no='.$episode->getEpisodeNo().'&patient_no='.$episode->getPatientNo()) ?>"><?php echo $episode->getPatientNo() ?></a></td>
      <td><?php echo $episode->getStatus() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('consultancy/new') ?>">New</a>
