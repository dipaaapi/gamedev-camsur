<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Announcement;
use App\Models\StudentBoardPost;
use Illuminate\Database\Seeder;

class PublicInfoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Calendar Events
        CalendarEvent::create([
            'date' => '2026-06-05',
            'type' => 'event',
            'time' => '09:00 AM',
            'title' => 'Basic Unity Course Graduation',
            'description' => 'Celebrating the success of basic course graduates at the Provincial Capitol.'
        ]);
        CalendarEvent::create([
            'date' => '2026-06-15',
            'type' => 'announcement',
            'time' => '08:00 AM',
            'title' => 'Enrollment Open: Advanced Course',
            'description' => 'Application opens for advanced game design & programming classes starting July.'
        ]);
        CalendarEvent::create([
            'date' => '2026-06-25',
            'type' => 'event',
            'time' => '01:00 PM',
            'title' => 'Indie Game Showcasing & Jam',
            'description' => 'Students present prototype titles to local developers and advisory panel.'
        ]);
        CalendarEvent::create([
            'date' => '2026-06-29',
            'type' => 'announcement',
            'time' => '10:00 AM',
            'title' => 'Academic Maintenance Shutdown',
            'description' => 'Main laboratory offline for server upgrades between 10 AM and 3 PM.'
        ]);
        CalendarEvent::create([
            'date' => '2026-07-02',
            'type' => 'event',
            'time' => '09:00 AM',
            'title' => 'Advanced Course Orientation',
            'description' => 'Welcoming the incoming cohort of Advanced Game Development students.'
        ]);
        CalendarEvent::create([
            'date' => '2026-07-10',
            'type' => 'event',
            'time' => '02:00 PM',
            'title' => 'Guest Lecture: Sound Design in Games',
            'description' => 'Industry veteran guest speaker discusses game soundtrack architecture.'
        ]);

        // 2. Seed Announcements
        Announcement::create([
            'date' => 'June 21, 2026',
            'title' => 'Scholarship Grant Application Deadline Extended',
            'excerpt' => 'Great news! The Provincial Government of Camarines Sur has extended the enrollment and scholarship application timeline.',
            'content' => "In response to numerous requests, the Provincial Government of Camarines Sur, under Governor LRay Villafuerte, has officially extended the application period for the upcoming tuition-free Game Development scholarship programs.\n\nNew Deadline: July 5, 2026.\n\nAll qualified senior high school graduates and ICT professionals resident in Camarines Sur are encouraged to complete registration online. Selected scholars receive fully-funded training, certification vouchers, and access to modern lab facilities at the CamSur IT Hub.",
            'author' => 'Administrator'
        ]);
        Announcement::create([
            'date' => 'June 18, 2026',
            'title' => 'Partnership with Global Studio for Internship Placements',
            'excerpt' => 'We have officially finalized an agreement with a major Southeast Asian game outsourcing firm to provide guaranteed internships.',
            'content' => "We are thrilled to announce a new partnership with regional game development giants to offer elite internship placements for our top graduates of the Advanced Game Development track. Under this cooperation, students will gain experience working directly on global AAA titles. Top performers may be offered full-time remote contracts upon graduation.",
            'author' => 'Administrator'
        ]);
        Announcement::create([
            'date' => 'June 10, 2026',
            'title' => 'New Hardware Upgrades Installed at CamSur IT Hub',
            'excerpt' => 'Lab computers updated with high-end GPUs to support high-fidelity real-time rendering and Unreal Engine dev workflow.',
            'content' => "To support modern development stacks including Unity, Unreal Engine 5, and Blender, our server infrastructure and workstation assets have been successfully upgraded. Over 40 terminals have been retrofitted with RTX workstations to provide lag-free real-time rendering for student projects.",
            'author' => 'Administrator'
        ]);
        Announcement::create([
            'date' => 'June 02, 2026',
            'title' => 'Unity Certification Exams Schedule Announced',
            'excerpt' => 'Schedule details and preparation guides for the official Unity Certified Associate exams this mid-year.',
            'content' => "Ready to certify your skillset? The official schedule for the upcoming Unity Certified User and Associate examinations is now finalized. Preparation review materials and practice simulation logins have been emailed to all currently enrolled seniors. Exams will take place at the main campus computer room on July 20-22.",
            'author' => 'Administrator'
        ]);

        // 3. Seed Student Board Posts
        StudentBoardPost::create([
            'category' => 'achievement',
            'date' => 'June 19, 2026',
            'title' => 'Student Team "PixelQuest" Wins National Game Jam',
            'content' => 'Our very own student team secured 1st Place with their climate awareness puzzle game "Rising Tides", featuring beautiful hand-drawn sprites and intuitive gameplay mechanics.',
            'author' => 'Jose Rizal Jr.',
            'author_initials' => 'JR'
        ]);
        StudentBoardPost::create([
            'category' => 'happening',
            'date' => 'June 15, 2026',
            'title' => 'Weekly Multiplayer Playtest Sessions Launched',
            'content' => 'Every Friday afternoon, students gather to playtest each other\'s multiplayer projects. It is a fantastic opportunity to gather real feedback, squash bugs, and bond as creators.',
            'author' => 'Clarissa Cruz',
            'author_initials' => 'CC'
        ]);
        StudentBoardPost::create([
            'category' => 'achievement',
            'date' => 'June 05, 2026',
            'title' => 'Indie Release Highlight: "Anito Realms" on Steam',
            'content' => 'Graduate student Alex Santos successfully launched his mythological action-adventure game on Steam, earning positive reviews and community praise in its early access phase.',
            'author' => 'Alex Santos',
            'author_initials' => 'AS'
        ]);
        StudentBoardPost::create([
            'category' => 'happening',
            'date' => 'May 28, 2026',
            'title' => 'Alumni Mentor Program Kickoff Session',
            'content' => 'Previous graduates who are now working in the industry returned to share insights, code reviews, and project feedback with the new batch of Basic Course students.',
            'author' => 'Sarah Jenkins',
            'author_initials' => 'SJ'
        ]);
    }
}
