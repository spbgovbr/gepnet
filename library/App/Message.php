<?php

class App_Message
{
    const EDIT_SUCCESS = 'Registro alterado com sucesso';
    const EDIT_ERROR = 'Erro ao tentar alterar o registro';
    const ADD_SUCCESS = 'Opera&ccedil;&atilde;o realizada com sucesso';
    const ADD_ERROR = 'Erro ao tentar inserir o registro';
    const DEL_SUCCESS = 'Opera&ccedil;&atilde;o realizada com sucesso';
    const DEL_ERROR = 'Erro ao tentar excluir o registro';

    public static function edit_error()
    {
        return self::EDIT_ERROR;
    }

    public static function edit_success()
    {
        return self::EDIT_SUCCESS;
    }

    public static function add_success()
    {
        return self::ADD_SUCCESS;
    }

    public static function add_error()
    {
        return self::ADD_ERROR;
    }

    public static function del_success()
    {
        return self::DEL_SUCCESS;
    }

    public static function del_error()
    {
        return self::DEL_ERROR;
    }
}