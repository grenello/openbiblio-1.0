<!--
*********************************************************
*  Body Style
*********************************************************
-->
body {
  background-color: <?php echo OBIB_PRIMARY_BG;?>
}

<!--
*********************************************************
*  Font Styles
*********************************************************
-->
font.primary {
  color: <?php echo OBIB_PRIMARY_FONT_COLOR;?>;
  font-size: <?php echo OBIB_PRIMARY_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_PRIMARY_FONT_FACE;?>;
}
font.alt1 {
  color: <?php echo OBIB_ALT1_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT1_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT1_FONT_FACE;?>;
}
font.alt1tab {
  color: <?php echo OBIB_ALT1_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
}
font.alt2 {
  color: <?php echo OBIB_ALT2_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
}
font.error {
  color: <?php echo OBIB_PRIMARY_ERROR_COLOR;?>;
  font-size: <?php echo OBIB_PRIMARY_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_PRIMARY_FONT_FACE;?>;
  font-weight: bold;
}
font.small {
  font-size: 10px;
}
h1 {
  font-size: 16px;
  font-family: <?php echo OBIB_PRIMARY_FONT_FACE;?>;
  font-weight: normal;
}

<!--
*********************************************************
*  Link Styles
*********************************************************
-->
a:link {
  color: <?php echo OBIB_PRIMARY_LINK_COLOR;?>;
}
a:visited {
  color: <?php echo OBIB_PRIMARY_LINK_COLOR;?>;
}
a.primary:link {
  color: <?php echo OBIB_PRIMARY_LINK_COLOR;?>;
}
a.primary:visited {
  color: <?php echo OBIB_PRIMARY_LINK_COLOR;?>;
}
a.alt1:link {
  color: <?php echo OBIB_ALT1_LINK_COLOR;?>;
}
a.alt1:visited {
  color: <?php echo OBIB_ALT1_LINK_COLOR;?>;
}
a.alt2:link {
  color: <?php echo OBIB_ALT2_LINK_COLOR;?>;
}
a.alt2:visited {
  color: <?php echo OBIB_ALT2_LINK_COLOR;?>;
}
a.tab:link {
  color: <?php echo OBIB_ALT2_LINK_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  text-decoration: none
}
a.tab:visited {
  color: <?php echo OBIB_ALT2_LINK_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  text-decoration: none
}
a.tab:hover {text-decoration: underline}

<!--
*********************************************************
*  Table Styles
*********************************************************
-->
table.primary {border-collapse: collapse}
table.border {
  border-style: solid;
  border-color: <?php echo OBIB_BORDER_COLOR;?>;
  border-width: <?php echo OBIB_BORDER_WIDTH;?>
}
th {
  background-color: <?php echo OBIB_ALT2_BG;?>;
  color: <?php echo OBIB_ALT2_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
  padding: <?php echo OBIB_PADDING;?>;
  border-style: solid;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  border-color: <?php echo OBIB_BORDER_COLOR;?>;
  border-width: <?php echo OBIB_BORDER_WIDTH;?>
}
td.primary {
  background-color: <?php echo OBIB_PRIMARY_BG;?>;
  color: <?php echo OBIB_PRIMARY_FONT_COLOR;?>;
  font-size: <?php echo OBIB_PRIMARY_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_PRIMARY_FONT_FACE;?>;
  padding: <?php echo OBIB_PADDING;?>;
  border-style: solid;
  border-color: <?php echo OBIB_BORDER_COLOR;?>;
  border-width: <?php echo OBIB_BORDER_WIDTH;?>
}
td.title {
  background-color: <?php echo OBIB_TITLE_BG;?>;
  color: <?php echo OBIB_TITLE_FONT_COLOR;?>;
  font-size: <?php echo OBIB_TITLE_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_TITLE_FONT_FACE;?>;
  padding: <?php echo OBIB_PADDING;?>;
<?php if (OBIB_TITLE_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  border-color: <?php echo OBIB_BORDER_COLOR;?>;
  border-width: <?php echo OBIB_BORDER_WIDTH;?>;
  text-align: <?php echo OBIB_TITLE_ALIGN;;?>
}
td.alt1 {
  background-color: <?php echo OBIB_ALT1_BG;?>;
  color: <?php echo OBIB_ALT1_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT1_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT1_FONT_FACE;?>;
  padding: <?php echo OBIB_PADDING;?>;
  border-style: solid;
  border-color: <?php echo OBIB_BORDER_COLOR;?>;
  border-width: <?php echo OBIB_BORDER_WIDTH;?>
}
td.tab1 {
  background-color: <?php echo OBIB_ALT1_BG;?>;
  color: <?php echo OBIB_ALT1_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT1_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  padding: <?php echo OBIB_PADDING;?>;
}
td.tab2 {
  background-color: <?php echo OBIB_ALT2_BG;?>;
  color: <?php echo OBIB_ALT2_FONT_COLOR;?>;
  font-size: <?php echo OBIB_ALT2_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_ALT2_FONT_FACE;?>;
<?php if (OBIB_ALT2_FONT_BOLD) { ?>
  font-weight: bold;
<?php } else { ?>
  font-weight: normal;
<?php } ?>
  padding: <?php echo OBIB_PADDING;?>;
}
td.noborder {
  background-color: <?php echo OBIB_PRIMARY_BG;?>;
  color: <?php echo OBIB_PRIMARY_FONT_COLOR;?>;
  font-size: <?php echo OBIB_PRIMARY_FONT_SIZE;?>px;
  font-family: <?php echo OBIB_PRIMARY_FONT_FACE;?>;
  padding: <?php echo OBIB_PADDING;?>;
}
