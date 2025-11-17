<?php
// filepath: d:\Software\Apache24\htdocs\hotel-reservas\views\pagos\recibo.php

$pago = $data['pago'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Pago #<?= $pago['id'] ?> - <?= APP_NAME ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .recibo {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #333;
            padding: 30px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        
        .header p {
            color: #7f8c8d;
            margin: 2px 0;
        }
        
        .recibo-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .recibo-info h2 {
            font-size: 18px;
            color: #28a745;
            margin-bottom: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-item {
            padding: 10px;
            background: #f8f9fa;
            border-radius: 4px;
        }
        
        .info-item label {
            display: block;
            font-weight: bold;
            color: #7f8c8d;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-item span {
            display: block;
            font-size: 14px;
            color: #2c3e50;
        }
        
        .monto-principal {
            text-align: center;
            background: #28a745;
            color: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        
        .monto-principal h3 {
            font-size: 14px;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .monto-principal .cantidad {
            font-size: 36px;
            font-weight: bold;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .table th,
        .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th {
            background: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #333;
            text-align: center;
            color: #7f8c8d;
        }
        
        .firma {
            margin-top: 60px;
            text-align: center;
        }
        
        .firma-linea {
            border-top: 1px solid #333;
            width: 300px;
            margin: 0 auto 10px;
        }
        
        .estado-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .estado-completado {
            background: #d4edda;
            color: #155724;
        }
        
        .estado-cancelado {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media print {
            body {
                padding: 0;
            }
            
            .recibo {
                border: none;
                max-width: 100%;
            }
            
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    
    <div class="recibo">
        
        <!-- Encabezado -->
        <div class="header">
            <h1><?= APP_NAME ?></h1>
            <p>Sistema de Gestión Hotelera</p>
            <p>📞 +1 234-567-8900 | ✉️ info@hotelreservas.com</p>
        </div>
        
        <!-- Información del Recibo -->
        <div class="recibo-info">
            <h2>RECIBO DE PAGO</h2>
            <div style="display: flex; justify-content: space-between; margin-top: 10px;">
                <div>
                    <strong>Recibo N°:</strong> #<?= str_pad($pago['id'], 6, '0', STR_PAD_LEFT) ?>
                </div>
                <div>
                    <strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pago['fecha_pago'])) ?>
                </div>
                <div>
                    <strong>Estado:</strong>
                    <span class="estado-badge estado-<?= $pago['estado'] ?>">
                        <?= ucfirst($pago['estado']) ?>
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Monto Principal -->
        <div class="monto-principal">
            <h3>MONTO PAGADO</h3>
            <div class="cantidad">$<?= number_format($pago['monto'], 2) ?></div>
            <p style="margin-top: 10px; opacity: 0.9;">
                <?= ucfirst($pago['metodo_pago']) ?>
            </p>
        </div>
        
        <!-- Información en Grid -->
        <div class="info-grid">
            <div class="info-item">
                <label>Cliente</label>
                <span><?= htmlspecialchars($pago['cliente_nombre']) ?></span>
                <?php if (!empty($pago['cliente_documento'])): ?>
                    <small style="color: #7f8c8d;">Doc: <?= htmlspecialchars($pago['cliente_documento']) ?></small>
                <?php endif; ?>
            </div>
            
            <div class="info-item">
                <label>Código de Reserva</label>
                <span><?= htmlspecialchars($pago['codigo_reserva']) ?></span>
            </div>
            
            <div class="info-item">
                <label>Habitación</label>
                <span>#<?= htmlspecialchars($pago['habitacion_numero']) ?> - <?= htmlspecialchars($pago['tipo_habitacion']) ?></span>
            </div>
            
            <div class="info-item">
                <label>Período de Estadía</label>
                <span>
                    <?= date('d/m/Y', strtotime($pago['fecha_entrada'])) ?>
                    al
                    <?= date('d/m/Y', strtotime($pago['fecha_salida'])) ?>
                </span>
            </div>
            
            <?php if (!empty($pago['referencia'])): ?>
            <div class="info-item">
                <label>Referencia / Transacción</label>
                <span><?= htmlspecialchars($pago['referencia']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Detalle del Pago -->
        <table class="table">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th style="text-align: right;">Monto</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        Pago por Reserva <?= htmlspecialchars($pago['codigo_reserva']) ?>
                        <br>
                        <small style="color: #7f8c8d;">
                            Método: <?= ucfirst($pago['metodo_pago']) ?>
                        </small>
                    </td>
                    <td style="text-align: right; font-weight: bold; color: #28a745;">
                        $<?= number_format($pago['monto'], 2) ?>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa;">
                    <th>TOTAL PAGADO</th>
                    <th style="text-align: right; font-size: 16px; color: #28a745;">
                        $<?= number_format($pago['monto'], 2) ?>
                    </th>
                </tr>
            </tfoot>
        </table>
        
        <?php if (!empty($pago['notas'])): ?>
        <div class="info-item" style="margin: 20px 0;">
            <label>Observaciones</label>
            <span><?= nl2br(htmlspecialchars($pago['notas'])) ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Firma -->
        <div class="firma">
            <div class="firma-linea"></div>
            <p><strong>Firma y Sello</strong></p>
            <p style="color: #7f8c8d; font-size: 11px;">Cajero / Recepcionista</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Este recibo es un documento válido para fines contables</p>
            <p>Generado el <?= date('d/m/Y H:i:s') ?></p>
            <p style="margin-top: 10px; font-size: 10px;">
                <?= APP_NAME ?> - Sistema de Gestión Hotelera
            </p>
        </div>
        
    </div>
    
    <!-- Botones de Acción (no se imprimen) -->
    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 30px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; margin-right: 10px;">
            🖨️ Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 30px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px;">
            ❌ Cerrar
        </button>
    </div>
    
</body>
</html>