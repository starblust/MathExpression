# MathExpression
A package that allows to calculate a math expressions
<h2>Installing:</h2>
<ul>
<li>via composer:
<p> add this in your composer.json
<pre>
"repositories": [
  {
    "type" : "vcs",
    "url" : "https://github.com/starblust/MathExpression.git"
  }
],
"require": {
  "starblust/math-expression" : "dev-master"
}
</pre>
<li>download file MathExpression.php and include it
</ul>
<h2>Using:</h2>
<p>
$formula = '3-2^3+(1+1)';
<p>
<pre>
$result = Starblust\MathExpression::calc($formula);<br>
if ($result === null){
  echo Starblust\MathExpression::getError();
}
else{
  echo $result;
}
</pre>