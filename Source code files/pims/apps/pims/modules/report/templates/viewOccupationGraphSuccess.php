<link href="/js/jsgraph/charting/css/basic.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jsgraph/_shared/EnhanceJS/enhance.js"></script>
<script type="text/javascript">
    // Run capabilities test
    enhance({
        loadScripts: [
            '/js/jsgraph/charting/js/excanvas.js',
            '/js/jsgraph/_shared/jquery.min.js',
            '/js/jsgraph/charting/js/visualize.jQuery.js',
            '/js/jsgraph/charting/js/example.js'
        ],
        loadStyles: [
            '/js/jsgraph/charting/css/visualize.css',
            '/js/jsgraph/charting/css/visualize-light.css'
        ]
    });
</script>
<br />
<table >
    <caption >Occupational Categories Of STD Clinic Attendees -<?php echo $year; ?></caption>
    <thead>
        <tr>
            <td></td>
            <th scope="col">CSW</th>
            <th scope="col">Student</th>
            <th scope="col">Retired</th>
            <th scope="col">Unemployed</th>
            <th scope="col">Employed</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach($chartData as $key=>$data) {

            ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>

            <td><?php echo $data['CSW']; ?></td>
            <td><?php echo $data['Student']; ?></td>
            <td><?php echo $data['Retired']; ?></td>
            <td><?php echo $data['Unemployed']; ?></td>
            <td><?php echo $data['Employed']; ?></td>

                <?php }//}?>

        </tr>
    </tbody>
</table>

