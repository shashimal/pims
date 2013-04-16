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
    <caption >Marital Status Of STD Clinic Attendees -<?php echo $year; ?></caption>
    <thead>
        <tr>
            <td></td>
            <th scope="col">Single</th>
            <th scope="col">Married</th>
            <th scope="col">Sep / Divo</th>
            <th scope="col">Widowed</th>
            <th scope="col">Living Together</th>
            <th scope="col">Not Known</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach($chartData as $key=>$data) {

            ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>

            <td><?php echo $data['Single']; ?></td>
            <td><?php echo $data['Married']; ?></td>
            <td><?php echo $data['SepDivo']; ?></td>
            <td><?php echo $data['Widowed']; ?></td>
            <td><?php echo $data['LivingTogether']; ?></td>
            <td><?php echo $data['NotKnown']; ?></td>

                <?php }//}?>

        </tr>
    </tbody>
</table>

