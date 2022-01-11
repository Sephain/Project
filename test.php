<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <title>chartist-plugin-legend examples</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.2.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.9.8/chartist.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/styles/default.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Oxygen:400,300,700%7CSource+Code+Pro:400,700" media="all">
    <link rel="stylesheet" href="styles/graph.css">

</head>
<body>
   <div style="max-width: 700px; margin: 0 auto 40px auto;">
      <div class="ct-chart ct-chart-line"></div>

   </div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/chartist/0.9.8/chartist.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.6.0/highlight.min.js"></script>
   <script>hljs.initHighlightingOnLoad();</script>
   <script src="scripts/chartist-plugin-legend.js"></script>

   <script>
      new Chartist.Line('.ct-chart-line', {
         labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
         series: [
             [12, 9, 7, 8, 5],
             [2, 1, 3.5, 7, 3],
             [1, 3, 4, 5, 6]
         ]
      }, {
         fullWidth: true,
         chartPadding: {
            right: 40
         },
         plugins: [
             Chartist.plugins.legend({
                legendNames: ['Blue pill', 'Red pill', 'Purple pill'],
             })
         ]
      });
</script>
</body>

</html>