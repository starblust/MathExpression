# MathExpression
A package that allows to calculate a math expressions
<h2>Installing:</h2>
<ul>
<li>via composer: composer install starblust/math-expression
<li>download file MathExpression.php and include it
</ul>
<h2>Using:</h2>
<p>
$formula = '3-2^3+(1+1)';
<p>
$result = Starblust\MathExpression::calc($formula);<br>
if ($result === null){<br>
  echo Starblust\MathExpression::getError();<br>
}<br>
else{<br>
  echo $result;<br>
}<br>