

<?php if ($r["status"] == "OPEN") { ?>

    <?php if ($r["thread_id"]) { ?>
        <form method="post" action="admin-actions.php" class="action-form">
            <input type="hidden" name="action" value="remove_thread">
            <input type="hidden" name="view" value="<?php echo $view; ?>">
            <input type="hidden" name="thread_id" value="<?php echo $r["thread_id"]; ?>">
            <button type="submit" class="btn btn-small btn-lime"
                onclick="return confirm('Remove this thread?')">Remove thread</button>
        </form>
    <?php } else { ?>
        <form method="post" action="admin-actions.php" class="action-form">
            <input type="hidden" name="action" value="remove_comment">
            <input type="hidden" name="view" value="<?php echo $view; ?>">
            <input type="hidden" name="comment_id" value="<?php echo $r["comment_id"]; ?>">
            <button type="submit" class="btn btn-small btn-lime"
                onclick="return confirm('Delete this comment?')">Remove comment</button>
        </form>
    <?php } ?>

    <?php if ($r["target_role"] == "USER" && $r["target_status"] == "ACTIVE") { ?>
        <form method="post" action="admin-actions.php" class="action-form">
            <input type="hidden" name="action" value="ban_user">
            <input type="hidden" name="view" value="<?php echo $view; ?>">
            <input type="hidden" name="user_id" value="<?php echo $r["target_id"]; ?>">
            <input type="hidden" name="report_id" value="<?php echo $r["report_id"]; ?>">
            <button type="submit" class="btn btn-small btn-pink"
                onclick="return confirm('Ban this user?')">Ban user</button>
        </form>
    <?php } else if ($r["target_status"] == "BANNED") { ?>
        <button type="button" class="btn btn-small btn-pink" disabled>Banned</button>
    <?php } ?>

<?php } ?>

<form method="post" action="admin-actions.php" class="action-form">
    <input type="hidden" name="action" value="dismiss_report">
    <input type="hidden" name="view" value="<?php echo $view; ?>">
    <input type="hidden" name="report_id" value="<?php echo $r["report_id"]; ?>">
    <button type="submit" class="btn btn-small btn-white"
        onclick="return confirm('Dismiss this report? The record will be deleted.')">Dismiss</button>
</form>
