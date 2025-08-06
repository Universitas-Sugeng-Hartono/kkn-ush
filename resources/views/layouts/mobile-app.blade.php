<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0d6efd">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">

    <title>{{ config('app.name', 'KKN-USH') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/ush.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/ush.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ asset("assets/fonts/inter.css") }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="{{ asset("assets/css/fontawesome.min.css") }}" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="{{ asset("assets/css/bootstrap.min.css") }}" rel="stylesheet">

    <style>
        /* Reset and Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff;
            color: #1a202c;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Mobile App Container */
        .mobile-app {
            max-width: 480px;
            margin: 0 auto;
            background: #ffffff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        /* Header */
        .app-header {
            background: #ffffff;
            color: #1a202c;
            padding: 1rem;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            border-bottom: 1px solid #f1f3f4;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .app-title {
            font-size: 1.125rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #1a202c;
        }

        .app-logo {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .app-logo img {
            width: 100%;
            height: auto;
            max-width: 32px;
            max-height: 32px;
            object-fit: contain;
        }

        .header-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border: none;
            border-radius: 50%;
            background: #f8f9fa;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-icon:hover {
            background: #e9ecef;
            color: #495057;
        }

        /* Main Content */
        .app-content {
            padding: 1rem;
            padding-bottom: 100px;
            background: #ffffff;
        }

        /* Cards */
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            border: 1px solid #f1f3f4;
            transition: all 0.2s ease;
            color: #1a202c;
        }

        .card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            color: #0d6efd;
            background: #f8f9fa;
        }

        .card-body {
            color: #1a202c;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .action-card {
            background: #ffffff;
            color: #1a202c;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            text-decoration: none;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid #f1f3f4;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .action-card:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-decoration: none;
            color: #1a202c;
        }

        .action-icon {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: #0d6efd;
        }

        .action-title {
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #1a202c;
        }

        .action-subtitle {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            border: 1px solid #f1f3f4;
            color: #1a202c;
        }

        .stat-number {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
        }

        /* Recent Activities */
        .activity-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            background: #f8f9fa;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 480px;
            background: #0B1F3A;
            border-top: 1px solid #163057;
            padding: 0.75rem 1rem;
            box-shadow: 0 -2px 8px rgba(11, 31, 58, 0.3);
            z-index: 1000;
        }

        .nav-items {
            display: flex;
            justify-content: space-around;
            align-items: center;
            gap: 0.25rem;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.5rem;
            border-radius: 6px;
            position: relative;
        }

        .nav-item:hover {
            color: rgba(255, 255, 255, 0.9);
            background: rgba(255, 255, 255, 0.05);
            transform: translateY(-1px);
        }

        .nav-item:hover .nav-icon {
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item:hover .nav-label {
            color: rgba(255, 255, 255, 0.9);
        }

        .nav-item.active {
            color: #F2B705;
            background: rgba(242, 183, 5, 0.15);
        }

        .nav-icon {
            font-size: 1.25rem;
            margin-bottom: 0.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.7);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item.active .nav-label {
            color: #F2B705;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 0.5rem;
            font-size: 0.875rem;
        }

        .btn-primary {
            background: #0d6efd;
            color: #ffffff;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(13, 110, 253, 0.2);
            color: #ffffff;
        }

        .btn-accent {
            background: #ffc107;
            color: #000000;
        }

        .btn-accent:hover {
            background: #e0a800;
            transform: translateY(-1px);
            color: #000000;
        }

        .btn-outline {
            background: transparent;
            color: #0d6efd;
            border: 2px solid #0d6efd;
        }

        .btn-outline:hover {
            background: #0d6efd;
            color: #ffffff;
        }



        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            border-color: #28a745;
            color: #28a745;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.1);
            border-color: #dc3545;
            color: #dc3545;
        }

        .alert-warning {
            background: rgba(255, 193, 7, 0.1);
            border-color: #ffc107;
            color: #ffc107;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .mobile-app {
                max-width: 100%;
            }
            
            .quick-actions {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Force light theme */
        .mobile-app {
            background: #ffffff !important;
            color: #1a202c !important;
        }

        .mobile-app .card {
            background: #ffffff !important;
            color: #1a202c !important;
        }

        .mobile-app .app-header {
            background: #ffffff !important;
            color: #1a202c !important;
        }

        .mobile-app .bottom-nav {
            background: #0B1F3A !important;
            color: #ffffff !important;
        }

        /* New Modern Dashboard Styles */
        .welcome-header {
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
            color: white;
            border-radius: 0 0 20px 20px;
            margin: -1rem -1rem 1.5rem -1rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            padding: 1.5rem 1rem;
            background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
            color: white;
            border-radius: 0 0 20px 20px;
            margin: -1rem -1rem 1.5rem -1rem;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .header-left h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .header-left .date-info {
            margin: 0;
            font-size: 0.875rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: rgba(242, 183, 5, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .user-details h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .date-info {
            margin: 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }



        /* Stats Section */
        .stats-section {
            margin-bottom: 2rem;
        }

        .stats-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .stat-item {
            background: white;
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stat-icon.logbook {
            background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
        }

        .stat-icon.attendance {
            background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
        }

        .stat-icon.pending {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        }

        .stat-icon.total {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .stat-content {
            flex: 1;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a202c;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            font-weight: 500;
            margin-top: 0.25rem;
        }

        /* Actions Section */
        .actions-section {
            margin-bottom: 2rem;
        }

        .actions-section h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 1rem;
        }

        .action-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.2s ease;
            border: 1px solid #f1f3f4;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }



        .action-btn.primary {
            background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #F2B705 0%, #d9a404 100%);
            color: #0B1F3A;
            position: relative;
            overflow: hidden;
        }

        /* Different shimmer timing for each button */
        .action-btn.primary::before {
            animation-delay: 0s;
        }

        .action-btn.secondary::before {
            animation-delay: 2.5s;
        }

        /* Different pulse timing for each button */
        .action-btn.primary::after {
            animation-delay: 0s;
        }

        .action-btn.secondary::after {
            animation-delay: 2.5s;
        }

        /* Shimmer Effect */
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.6),
                transparent
            );
            transition: left 0.5s;
            animation: shimmer 5s infinite;
            z-index: 1;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            15% {
                left: 100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Ensure content stays above shimmer */
        .action-btn i,
        .action-btn span {
            position: relative;
            z-index: 2;
        }

        /* Pulse ring effect */
        .action-btn::after {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 18px;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            opacity: 0;
            animation: pulse-ring 5s infinite;
            z-index: 0;
        }

        @keyframes pulse-ring {
            0% {
                opacity: 0;
                transform: scale(1);
            }
            10% {
                opacity: 1;
                transform: scale(1.05);
            }
            20% {
                opacity: 0;
                transform: scale(1.1);
            }
            100% {
                opacity: 0;
                transform: scale(1.1);
            }
        }



        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .action-btn.primary:hover {
            background: linear-gradient(135deg, #163057 0%, #1a3a6b 100%);
            box-shadow: 0 8px 25px rgba(11, 31, 58, 0.3), 0 0 20px rgba(11, 31, 58, 0.2);
        }

        .action-btn.secondary:hover {
            background: linear-gradient(135deg, #d9a404 0%, #e6b800 100%);
            box-shadow: 0 8px 25px rgba(242, 183, 5, 0.3), 0 0 20px rgba(242, 183, 5, 0.2);
        }

        /* Enhanced shimmer on hover */
        .action-btn:hover::before {
            animation: shimmer 0.8s infinite;
            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.8),
                transparent
            );
        }

        .action-btn i {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .action-btn span {
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Section Headers */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        .section-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        .view-all {
            color: #0B1F3A;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Activities Section */
        .activities-section {
            margin-bottom: 2rem;
        }

        .activities-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .activity-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s ease;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: #f8f9fa;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8f9fa;
            color: #0B1F3A;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .activity-time {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .activity-arrow {
            color: #cbd5e0;
            font-size: 0.875rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        /* Calendar Section */
        .calendar-section {
            margin-bottom: 2rem;
        }

        .calendar-preview {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .today-events {
            padding: 1rem;
        }

        .event-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s ease;
        }

        .event-item:last-child {
            border-bottom: none;
        }

        .event-item:hover {
            background: #f8f9fa;
        }

        .event-time {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #0B1F3A;
            font-weight: 600;
            font-size: 0.875rem;
            min-width: 80px;
        }

        .event-content {
            flex: 1;
            margin: 0 1rem;
        }

        .event-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .event-subtitle {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .event-status {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
        }

        .event-status.approved {
            background: #d4edda;
            color: #155724;
        }

        .no-events {
            text-align: center;
            padding: 2rem;
            color: #6c757d;
        }

        .no-events i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            opacity: 0.5;
        }

        .btn-add-event {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #0B1F3A;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            margin-top: 1rem;
            transition: all 0.2s ease;
        }

        .btn-add-event:hover {
            background: #163057;
            transform: translateY(-1px);
            text-decoration: none;
            color: white;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .action-buttons {
                grid-template-columns: 1fr;
            }
            
            .welcome-header {
                padding: 1rem;
            }
            
            .user-avatar {
                width: 40px;
                height: 40px;
                font-size: 1.25rem;
            }
            
            .user-details h4 {
                font-size: 1.125rem;
            }
        }

        /* Page Header Styles */
        .page-header {
            background: linear-gradient(135deg, #0B1F3A 0%, #163057 100%);
            color: white;
            padding: 1.5rem 1rem;
            border-radius: 0 0 20px 20px;
            margin: -1rem -1rem 1.5rem -1rem;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-left h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .header-left .date-info {
            margin: 0.25rem 0 0 0;
            opacity: 0.9;
            font-size: 0.875rem;
        }

        .btn-add {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(242, 183, 5, 0.2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-add:hover {
            background: rgba(242, 183, 5, 0.3);
            transform: scale(1.1);
            color: white;
        }

        /* Calendar Styles */
        .calendar-section {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .calendar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }

        .calendar-header h3 {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: #f8f9fa;
            color: #0B1F3A;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .nav-btn:hover {
            background: #0B1F3A;
            color: white;
        }

        .current-month {
            font-weight: 600;
            color: #1a202c;
            min-width: 100px;
            text-align: center;
        }

        .calendar-grid {
            width: 100%;
        }

        .calendar-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .day-header {
            text-align: center;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
            padding: 0.5rem 0;
        }

        .calendar-dates {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-date {
            aspect-ratio: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            position: relative;
            font-size: 0.875rem;
            min-width: 0;
            min-height: 0;
        }

        /* Responsive untuk mobile */
        @media (max-width: 480px) {
            .calendar-days,
            .calendar-dates {
                grid-template-columns: repeat(7, 1fr);
                gap: 0.25rem;
            }
            
            .calendar-date {
                font-size: 0.75rem;
                padding: 0.25rem;
            }
            
            .day-header {
                font-size: 0.75rem;
                padding: 0.25rem 0;
            }
        }

        .calendar-date.current-month {
            color: #1a202c;
        }

        .calendar-date.other-month {
            color: #cbd5e0;
        }

        .calendar-date.today {
            background: #0B1F3A;
            color: white;
            font-weight: 600;
        }

        .calendar-date.has-event {
            font-weight: 600;
        }

        .calendar-date:hover {
            background: #f8f9fa;
        }

        .calendar-date.today:hover {
            background: #163057;
        }

        .event-dot {
            width: 4px;
            height: 4px;
            background: #F2B705;
            border-radius: 50%;
            position: absolute;
            bottom: 4px;
        }

        .calendar-date.today .event-dot {
            background: #F2B705;
        }

        /* Events Section */
        .events-section {
            margin-bottom: 2rem;
        }

        .events-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .today-date {
            color: #0B1F3A;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Recent Section */
        .recent-section {
            margin-bottom: 2rem;
        }

        .logbooks-list {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
        }

        .logbook-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #f1f3f4;
            transition: background-color 0.2s ease;
            cursor: pointer;
        }

        .logbook-item:last-child {
            border-bottom: none;
        }

        .logbook-item:hover {
            background: #f8f9fa;
        }

        .logbook-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #f8f9fa;
            color: #0B1F3A;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .logbook-content {
            flex: 1;
        }

        .logbook-title {
            font-weight: 600;
            color: #1a202c;
            margin-bottom: 0.25rem;
            font-size: 0.875rem;
        }

        .logbook-date {
            font-size: 0.75rem;
            color: #6c757d;
        }

        .logbook-status {
            margin-right: 0.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .logbook-status.approved .status-badge {
            background: #d4edda;
            color: #155724;
        }

        .logbook-status.rejected .status-badge {
            background: #f8d7da;
            color: #721c24;
        }

        .logbook-status.submitted .status-badge {
            background: #d1ecf1;
            color: #0c5460;
        }

        .logbook-status.draft .status-badge {
            background: #e2e3e5;
            color: #383d41;
        }

        .logbook-arrow {
            color: #cbd5e0;
            font-size: 0.875rem;
        }

        .btn-add-logbook {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: #0B1F3A;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            margin-top: 1rem;
            transition: all 0.2s ease;
        }

        .btn-add-logbook:hover {
            background: #163057;
            transform: translateY(-1px);
            text-decoration: none;
            color: white;
        }

        /* Bottom Navigation - Update colors */
        .nav-item.active {
            color: #F2B705;
            background: rgba(242, 183, 5, 0.2);
            box-shadow: 0 0 10px rgba(242, 183, 5, 0.3);
            transform: translateY(-2px);
        }

        .nav-item.active .nav-icon {
            color: #F2B705;
            text-shadow: 0 0 8px rgba(242, 183, 5, 0.5);
        }

        .nav-item.active .nav-label {
            color: #F2B705;
            font-weight: 600;
            text-shadow: 0 0 4px rgba(242, 183, 5, 0.3);
        }


    </style>

    @stack('styles')
</head>
<body>
    <div class="mobile-app">
        <!-- Header -->
        <header class="app-header">
            <div class="header-content">
                <div class="app-title">
                    <div class="app-logo">
                        <img src="{{ asset('images/ush.png') }}" alt="USH Logo">
                    </div>
                    <span>KKN-USH</span>
                </div>
                <div class="header-actions">
                    <button class="btn-icon" onclick="toggleNotifications()">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button class="btn-icon" onclick="toggleProfile()">
                        <i class="fas fa-user"></i>
                    </button>
                </div>
            </div>
        </header>



        <!-- Main Content -->
        <main class="app-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Bottom Navigation -->
        <nav class="bottom-nav">
            <div class="nav-items">
                <a href="{{ route('mobile.dashboard') }}" class="nav-item {{ request()->routeIs('mobile.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home nav-icon"></i>
                    <span class="nav-label">Dashboard</span>
                </a>
                <a href="{{ route('mobile.logbooks') }}" class="nav-item {{ request()->routeIs('mobile.logbooks*') ? 'active' : '' }}">
                    <i class="fas fa-book nav-icon"></i>
                    <span class="nav-label">Logbook</span>
                </a>
                <a href="{{ route('mobile.attendance') }}" class="nav-item {{ request()->routeIs('mobile.attendance*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check nav-icon"></i>
                    <span class="nav-label">Absensi</span>
                </a>
                <a href="{{ route('mobile.notifications') }}" class="nav-item {{ request()->routeIs('mobile.notifications*') ? 'active' : '' }}">
                    <i class="fas fa-bell nav-icon"></i>
                    <span class="nav-label">Notif</span>
                </a>
                <a href="{{ route('mobile.profile') }}" class="nav-item {{ request()->routeIs('mobile.profile*') ? 'active' : '' }}">
                    <i class="fas fa-user nav-icon"></i>
                    <span class="nav-label">Profile</span>
                </a>
            </div>
        </nav>
    </div>

    <!-- Scripts -->
    <script src="{{ asset("assets/js/jquery.min.js") }}"></script>
    <script src="{{ asset("assets/js/bootstrap.bundle.min.js") }}"></script>
    <script src="{{ asset("assets/js/sweetalert2.min.js") }}"></script>

    <script>
        // Simple functions for mobile app
        function toggleNotifications() {
            window.location.href = '{{ route("mobile.notifications") }}';
        }

        function toggleProfile() {
            window.location.href = '{{ route("mobile.profile") }}';
        }



        // Device detection
        document.addEventListener('DOMContentLoaded', function() {
            // Send device info to server
            fetch('{{ route("device.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    is_mobile: true,
                    is_tablet: false,
                    is_desktop: false,
                    screen_width: window.innerWidth,
                    screen_height: window.innerHeight,
                    user_agent: navigator.userAgent
                })
            }).catch(error => {
                console.log('Device info update failed:', error);
            });
        });
    </script>

    @stack('scripts')
</body>
</html> 