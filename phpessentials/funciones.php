<?php
    function getEstadoClass($tipo) {
        switch(strtolower($tipo)) {
            case 'excelente':
                return 'text-success fw-bold';
            case 'bien':
                return 'text-primary fw-bold';
            case 'defectuoso':
                return 'text-danger fw-bold';
            default:
                return 'text-muted';
        }
    }
?>
