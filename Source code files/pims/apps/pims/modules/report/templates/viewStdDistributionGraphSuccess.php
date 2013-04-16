<link href="/js/jsgraph/charting/css/basic.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/js/jsgraph/_shared/EnhanceJS/enhance.js"></script>
<script type="text/javascript">
    // Run capabilities test
    enhance({
        loadScripts: [
            '/js/jsgraph/charting/js/excanvas.js',
            '/js/jsgraph/_shared/jquery.min.js',
            '/js/jsgraph/charting/js/visualize.jQuery.js',
            '/js/jsgraph/charting/js/example1.js'
        ],
        loadStyles: [
            '/js/jsgraph/charting/css/visualize.css',
            '/js/jsgraph/charting/css/visualize-light.css'
        ]
    });
</script>
<br />

<table >
    <caption ><?php echo $stdName . " Distribution By Sex - ".$year; ?></caption>
    <thead>
        <tr>
            <td></td>
            <th scope="col">Male</th>
            <th scope="col">Female</th>            

        </tr>
    </thead>
    <tbody>
        <?php foreach($chartData as $key=>$data) {

            ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>

            <td><?php echo $data['m']; ?></td>
            <td><?php echo $data['f']; ?></td>
            

                <?php }//}?>

        </tr>
    </tbody>
</table>

