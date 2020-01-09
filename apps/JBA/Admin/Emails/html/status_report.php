<style type="text/css">
    #qa {
        width: 100%;
        border: 1px solid black;
    }

    #qa th {
        background-color: #E6E6E6;
    }
</style>

{{@default_header}}

<p>Attached is my weekly status update.</p>

<table id="qa" cellpadding="10">
    <?php foreach ($questions as $question): ?>
        <tr><th><?php echo $question['q'] ?></th></tr>
        <tr><td><?php echo $question['a'] ?><br><br></td></tr>
    <?php endforeach; ?>
</table>
<br><br>

{{@default_footer}}