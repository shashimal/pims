<table>
  <tbody>
    <tr>
      <th>Patient no:</th>
      <td><?php echo $patient->getPatientNo() ?></td>
    </tr>
    <tr>
      <th>Registered date:</th>
      <td><?php echo $patient->getRegisteredDate() ?></td>
    </tr>
    <tr>
      <th>First name:</th>
      <td><?php echo $patient->getFirstName() ?></td>
    </tr>
    <tr>
      <th>Last name:</th>
      <td><?php echo $patient->getLastName() ?></td>
    </tr>
    <tr>
      <th>Current address:</th>
      <td><?php echo $patient->getCurrentAddress() ?></td>
    </tr>
    <tr>
      <th>Permanent address:</th>
      <td><?php echo $patient->getPermanentAddress() ?></td>
    </tr>
    <tr>
      <th>Contact address:</th>
      <td><?php echo $patient->getContactAddress() ?></td>
    </tr>
    <tr>
      <th>Telephone1:</th>
      <td><?php echo $patient->getTelephone1() ?></td>
    </tr>
    <tr>
      <th>Telephone2:</th>
      <td><?php echo $patient->getTelephone2() ?></td>
    </tr>
    <tr>
      <th>Mobile:</th>
      <td><?php echo $patient->getMobile() ?></td>
    </tr>
    <tr>
      <th>Email:</th>
      <td><?php echo $patient->getEmail() ?></td>
    </tr>
    <tr>
      <th>Nic pp no:</th>
      <td><?php echo $patient->getNicPpNo() ?></td>
    </tr>
    <tr>
      <th>Date of birth:</th>
      <td><?php echo $patient->getDateOfBirth() ?></td>
    </tr>
    <tr>
      <th>Sex:</th>
      <td><?php echo $patient->getSex() ?></td>
    </tr>
    <tr>
      <th>Marital status:</th>
      <td><?php echo $patient->getMaritalStatus() ?></td>
    </tr>
    <tr>
      <th>Nationality:</th>
      <td><?php echo $patient->getNationality() ?></td>
    </tr>
    <tr>
      <th>Education:</th>
      <td><?php echo $patient->getEducation() ?></td>
    </tr>
    <tr>
      <th>Occupation:</th>
      <td><?php echo $patient->getOccupation() ?></td>
    </tr>
    <tr>
      <th>Category:</th>
      <td><?php echo $patient->getCategory() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('registration/edit?patient_no='.$patient->getPatientNo()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('registration/index') ?>">List</a>
