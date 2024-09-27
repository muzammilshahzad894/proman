<script>
    function toggleLongDescription() {
        var longDescription = document.getElementById('long-description');
        var addLongDescription = document.getElementById('add-long-description');
        var addLongDesIcon = document.getElementById('add-long-des-icon');
        if (longDescription.style.display === 'none') {
            longDescription.style.display = 'block';
            addLongDescription.innerHTML = 'Hide Long Description';
            addLongDesIcon.innerHTML = '-';
        } else {
            longDescription.style.display = 'none';
            addLongDescription.innerHTML = 'Add Long Description';
            addLongDesIcon.innerHTML = '+';
        }
    }
</script>