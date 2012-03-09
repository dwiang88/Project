<ul class="tabs">
	<li><a href="index.php" <?php if ($tab == "home") echo 'class="selected"'; ?>>Dashboard</a></li>
	<li><a href="todo.php" <?php if ($tab == "todos") echo 'class="selected"'; ?>>To dos</a></li>
	<li><a href="projects.php" <?php if ($tab == "projects") echo 'class="selected"'; ?>>Projects</a></li>
	<li><a href="tasks.php" <?php if ($tab == "tasks") echo 'class="selected"'; ?>>Tasks</a></li>
	<li><a href="clients.php" <?php if ($tab == "clients") echo 'class="selected"'; ?>>Clients</a></li>
	<li><a href="billing.php" <?php if ($tab == "billing") { echo 'class="selected"'; } if ($tab != "archive" && $tab != "billing") { echo 'class="secnav"'; } ?>>For Billing</a></li>
	<li><a href="archive.php" <?php if ($tab == "archive") { echo 'class="selected"'; } if ($tab != "archive" && $tab != "billing") { echo 'class="secnav"'; } ?>>Archive</a></li>
</ul>