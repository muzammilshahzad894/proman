<script>
    window.onload = refreshParent;
    function refreshParent() {
        window.opener.location.reload();
        window.close();
    }
</script>