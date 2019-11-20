<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<body>
  
<table width="100%">
  <tbody>

  <tr>
    <td>
      <table>
        <tr>
          <td width="23"></td>
          <td align="left" style="font: normal 12px/18px Helvetica, Arial, sans-serif; color: #7d7d7d;">
            <p>Hi <b>{$user->name|escape}</b></p>

            <p>You have just requested to reset your password.</p>
            
            <p>Follow the link below to reset it: </p>
            
            <p><a href='{$reset_pass_url}' style='color: #666; font: bold'><b>{$reset_pass_url}</b></a></p>

          </td>
        </tr>
      </table>
    </td>
  </tr>

  </tbody>
</table>
</body>
</html>