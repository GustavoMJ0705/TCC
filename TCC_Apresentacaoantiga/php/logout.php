<?php
session_start();
session_destroy();
header("Location: ../html/contas.html");
exit();