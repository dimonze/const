<table>
  <tbody>
    <tr>
      <th>Id:</th>
      <td><?php echo $parameters->getId() ?></td>
    </tr>
    <tr>
      <th>Params name:</th>
      <td><?php echo $parameters->getParamsName() ?></td>
    </tr>
    <tr>
      <th>Params value:</th>
      <td><?php echo $parameters->getParamsValue() ?></td>
    </tr>
    <tr>
      <th>Params type:</th>
      <td><?php echo $parameters->getParamsType() ?></td>
    </tr>
    <tr>
      <th>Optional:</th>
      <td><?php echo $parameters->getOptional() ?></td>
    </tr>
    <tr>
      <th>Description:</th>
      <td><?php echo $parameters->getDescription() ?></td>
    </tr>
    <tr>
      <th>Owner:</th>
      <td><?php echo $parameters->getOwner() ?></td>
    </tr>
  </tbody>
</table>

<hr />

<a href="<?php echo url_for('parameters/edit?id='.$parameters->getId()) ?>">Edit</a>
&nbsp;
<a href="<?php echo url_for('parameters/index') ?>">List</a>
