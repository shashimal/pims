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
    <caption >Reason For Attendance -<?php echo $year; ?></caption>
    <thead>
        <tr>
            <td></td>
            <th scope="col">Contact of patient</th>
            <th scope="col">Voluntary</th>
            <th scope="col">Referral from Magistrates</th>
            <th scope="col">Others</th>

        </tr>
    </thead>
    <tbody>
        <?php foreach($chartData as $key=>$data) {

            ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>

            <td><?php echo $data['contact']; ?></td>
            <td><?php echo $data['volantary']; ?></td>
            <td><?php echo $data['court']; ?></td>
            <td><?php echo $data['other']; ?></td>

                <?php }//}?>

        </tr>
    </tbody>
</table>

