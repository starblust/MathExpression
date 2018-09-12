<?php
namespace Starblust;

 /*
 ** calculating simple math expressions
 ** at now it supports only five operators
 */

class MathExpression {

  /**
  ** error description
  ** @var string $error
  */
  private static $error = '';

  /**
  ** list of operators and their priority
  ** operator => priority
  ** @var array $operators
  */
  private static $operators = [
    '^' => 4,
    '*' => 3,
    '/' => 3,
    '+' => 2,
    '-' => 2,
  ];

  /**
  ** @param string $math_string
  ** @return null|float - result of calculating
  */
  public static function calc(string $math_string) : ?float {
    self::$error = '';
    if (!$math_string){
      self::$error = 'empty math expression';
      return null;
    }
    $postfix = self::shunting_yard($math_string);
    if (!$postfix){
      self::$error = 'empty postfix string';
      return null;
    }
    return self::rpn($postfix);
  }

  /**
  ** @return string - error description
  */
  public static function getError() : string{
    return self::$error;
  }

  /**
  ** shunting yard
  ** @param string $formula
  ** @return array - list of tokens(numbers, operators)
  */
  private static function shunting_yard(string $formula) : ?array {
    if (!$formula){
      self::$error = 'empty formula';
      return null;
    }
    $formula = str_replace(' ', '', $formula);
    $f_len = strlen($formula);
    $output = [];
    $operator_stack = [];
    $op_precedence = self::$operators;
    $operators = array_keys($op_precedence);
    $num = '';
    for ($index = 0; $index < $f_len; $index++) {
      $token = $formula[$index];
      if (is_numeric($token) || $token === '.'){ // is number
        $num .= $token;
        $next_token = ($index+1) < $f_len ? $formula[$index+1] : '';
        if ($next_token !== '.' && !is_numeric($next_token)){
          $output[] = $num;
          $num = '';
        }
      }
      elseif (in_array($token, $operators)){ // is operator
        while($operator_stack &&
              '(' !== ($top_op = end($operator_stack)) &&
              ($op_precedence[$top_op] <=> $op_precedence[$token]) >= 0)
        {
          $output[] = array_pop($operator_stack);
        }
        $operator_stack[] = $token;
      }
      elseif ($token === '('){ // is left bracket
        $operator_stack[] = $token;
      }
      elseif ($token === ')'){ // is right bracket
        while('(' !== ($top_operator = array_pop($operator_stack))){
          $output[] = $top_operator;
        }
        if ($top_operator !== '('){
          self::$error = 'mismatched parentheses';
          return null;
        }
      }
    }
    if ($operator_stack){
      if ('(' === $operator_stack[0]){
        self::$error = 'mismatched parentheses';
        return null;
      }
      $output = array_merge($output, array_reverse($operator_stack));
    }
    return $output;
  }

  /**
  ** reverse polish notation
  ** @param array $tokens
  ** @return null|float - result of calculating
  */
  private static function rpn(array $tokens) : ?float{
    if (!$tokens){
      self::$error = 'empty array of tokens';
      return null;
    }
    $operand_stack = [];
    $operators = array_keys(self::$operators);
    foreach ($tokens as $token){
      if (in_array($token, $operators)){
        if (!$operand_stack){
          self::$error = 'operand stack is empty';
          return null;
        }
        $operand2 = array_pop($operand_stack);
        $operand1 = array_pop($operand_stack);
        switch ($token) {
          case '+':
            $eval_token = $operand1 + $operand2;
            break;
          case '-':
            $eval_token = $operand1 - $operand2;
            break;
          case '/':
            $eval_token = $operand1 / $operand2;
            break;
          case '*':
            $eval_token = $operand1 * $operand2;
            break;
          case '^':
            $eval_token = $operand1 ** $operand2;
            break;
        }
        $operand_stack[] = $eval_token;
      }
      else{
        $operand_stack[] = $token;
      }
    }
    if (count($operand_stack) === 1){
      return array_pop($operand_stack);
    }
    else{
      self::$error = 'incorrect evaluate result';
      return null;
    }
  }

}
