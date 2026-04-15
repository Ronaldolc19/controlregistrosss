<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #334155; }
        .header { border-bottom: 3px solid #007F3F; padding-bottom: 10px; margin-bottom: 30px; }
        .title { text-align: center; color: #0f172a; text-transform: uppercase; }
        .kpi-container { width: 100%; margin-bottom: 30px; }
        .kpi-box { background: #f8fafc; border: 1px solid #e2e8f0; padding: 15px; text-align: center; width: 30%; display: inline-block; margin: 1%; }
        .table { width: 100%; border-collapse: collapse; font-size: 10px; }
        .table th { background: #0f172a; color: #fff; padding: 8px; text-align: left; }
        .table td { border-bottom: 1px solid #e2e8f0; padding: 6px; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin:0;">TESVB - Servicio Social</h2>
        <small>Departamento de Vinculación Institucional</small>
    </div>

    <div class="title">
        <h1>Informe de Corte Administrativo {{ $ciclo }}</h1>
        <p>Fecha de emisión: {{ $fecha_cierre }}</p>
    </div>

    <div class="kpi-container">
        <div class="kpi-box">
            <div style="font-size: 18px; font-weight: bold; color: #007F3F;">{{ $total_estudiantes }}</div>
            <div style="font-size: 10px;">Estudiantes Activos</div>
        </div>
        <div class="kpi-box">
            <div style="font-size: 18px; font-weight: bold; color: #0ea5e9;">{{ $total_liberados }}</div>
            <div style="font-size: 10px;">Liberaciones</div>
        </div>
        <div class="kpi-box">
            <div style="font-size: 18px; font-weight: bold; color: #800020;">{{ $total_bajas }}</div>
            <div style="font-size: 10px;">Bajas Totales</div>
        </div>
    </div>

    <h3>Análisis de Distribución Académica</h3>
    <table class="table" style="margin-bottom: 30px;">
        <thead>
            <tr>
                <th>Carrera</th>
                <th style="text-align: center;">Alumnos</th>
                <th style="text-align: center;">% de Impacto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($por_carrera as $c)
            <tr>
                <td>{{ $c->perfil_profesional_carrera }}</td>
                <td style="text-align: center;">{{ $c->total }}</td>
                <td style="text-align: center;">{{ number_format(($c->total / max($total_estudiantes, 1)) * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">Este documento es un respaldo digital oficial del sistema TESVB.</div>
</body>
</html>