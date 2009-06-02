<?php
/**********************************************************************************
 *   Copyright(C) 2002 David Stevens
 *
 *   This file is part of OpenBiblio.
 *
 *   OpenBiblio is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 *   OpenBiblio is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with OpenBiblio; if not, write to the Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 **********************************************************************************
 */

  session_start();

  #****************************************************************************
  #*  Reset all form values
  #****************************************************************************
  if (isset($HTTP_GET_VARS["reset"])){
    unset($HTTP_SESSION_VARS["postVars"]);
    unset($HTTP_SESSION_VARS["pageErrors"]);
  }

  #****************************************************************************
  #*  Getting page errors and previous post variables from session.
  #****************************************************************************
  if (isset($HTTP_SESSION_VARS["postVars"])){
    $postVars = $HTTP_SESSION_VARS["postVars"];
  } else {
    $postVars = NULL;
  }
  if (isset($HTTP_SESSION_VARS["pageErrors"])){
    $pageErrors = $HTTP_SESSION_VARS["pageErrors"];
  } else {
    $pageErrors = NULL;
  }

?>
