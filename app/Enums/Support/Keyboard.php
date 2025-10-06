<?php

declare(strict_types=1);

namespace App\Enums\Support;

enum Keyboard: string
{
    case A = 'a';
    case B = 'b';
    case C = 'c';
    case D = 'd';
    case E = 'e';
    case F = 'f';
    case G = 'g';
    case H = 'h';
    case I = 'i';
    case J = 'j';
    case K = 'k';
    case L = 'l';
    case M = 'm';
    case N = 'n';
    case O = 'o';
    case P = 'p';
    case Q = 'q';
    case R = 'r';
    case S = 's';
    case T = 't';
    case U = 'u';
    case V = 'v';
    case W = 'w';
    case X = 'x';
    case Y = 'y';
    case Z = 'z';

    case Number0 = '0';
    case Number1 = '1';
    case Number2 = '2';
    case Number3 = '3';
    case Number4 = '4';
    case Number5 = '5';
    case Number6 = '6';
    case Number7 = '7';
    case Number8 = '8';
    case Number9 = '9';

    case Enter = "\n";
    case Space = ' ';
    case Escape = "\e";
    case Tab = "\t";

    public static function fromString(string $input): ?self
    {
        return self::tryFrom($input);
    }
}
