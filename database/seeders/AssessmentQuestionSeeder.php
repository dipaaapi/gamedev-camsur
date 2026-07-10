<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentQuestion;

class AssessmentQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = [
            [
                'question' => 'Which keyword in C# is used to declare a variable whose value is constant and cannot be changed after initialization?',
                'option_a' => 'A) static',
                'option_b' => 'B) const',
                'option_c' => 'C) var',
                'option_d' => 'D) mutable',
                'correct_option' => 'B',
            ],
            [
                'question' => 'Which built-in Unity callback method runs once per frame and is used for frame-rate dependent logic (like inputs and timers)?',
                'option_a' => 'A) Start()',
                'option_b' => 'B) Awake()',
                'option_c' => 'C) Update()',
                'option_d' => 'D) FixedUpdate()',
                'correct_option' => 'C',
            ],
            [
                'question' => 'What is the standard C# syntax to print debugging messages directly to the Unity Editor Console?',
                'option_a' => 'A) Console.WriteLine()',
                'option_b' => 'B) print()',
                'option_c' => 'C) Debug.Log()',
                'option_d' => 'D) System.Out.Println()',
                'correct_option' => 'C',
            ],
            [
                'question' => 'In C#, which generic collection should you use if you want to store a list of values but ensure that there are no duplicate entries?',
                'option_a' => 'A) List<T>',
                'option_b' => 'B) HashSet<T>',
                'option_c' => 'C) Array',
                'option_d' => 'D) Dictionary<TKey, TValue>',
                'correct_option' => 'B',
            ],
            [
                'question' => 'What component must be attached to a Unity GameObject for it to receive forces and be controlled by gravity?',
                'option_a' => 'A) Collider',
                'option_b' => 'B) Rigidbody',
                'option_c' => 'C) Transform',
                'option_d' => 'D) MeshRenderer',
                'correct_option' => 'B',
            ],
            [
                'question' => 'Which coordinates system is standard in Unity where the Y-axis points upward, X-axis points right, and Z-axis points forward?',
                'option_a' => 'A) Left-Handed Coordinate System',
                'option_b' => 'B) Right-Handed Coordinate System',
                'option_c' => 'C) Spherical Coordinate System',
                'option_d' => 'D) Polar Coordinate System',
                'correct_option' => 'A',
            ],
            [
                'question' => 'Which variable access modifier in C# hides the variable from other classes but allows it to be visible in the Unity Inspector?',
                'option_a' => 'A) public',
                'option_b' => 'B) [SerializeField] private',
                'option_c' => 'C) static',
                'option_d' => 'D) protected',
                'correct_option' => 'B',
            ],
            [
                'question' => 'In game development, what does the term "FPS" stand for when measuring engine performance?',
                'option_a' => 'A) First Person Shooter',
                'option_b' => 'B) Frames Per Second',
                'option_c' => 'C) First Phase Setup',
                'option_d' => 'D) Force Per Second',
                'correct_option' => 'B',
            ],
            [
                'question' => 'Which Unity compiler package is responsible for building modern UI elements using XML-like tags and CSS style sheets?',
                'option_a' => 'A) UXML',
                'option_b' => 'B) UI Toolkit',
                'option_c' => 'C) IMGUI',
                'option_d' => 'D) NGUI',
                'correct_option' => 'B',
            ],
            [
                'question' => 'In C#, what is the purpose of the \'using\' directive at the very top of a script file?',
                'option_a' => 'A) To declare local variables',
                'option_b' => 'B) To include external libraries or namespaces',
                'option_c' => 'C) To print logs to the console',
                'option_d' => 'D) To run conditional loops',
                'correct_option' => 'B',
            ],
            [
                'question' => 'Which data structure operates under the Last-In, First-Out (LIFO) rule?',
                'option_a' => 'A) Queue',
                'option_b' => 'B) Stack',
                'option_c' => 'C) List',
                'option_d' => 'D) Dictionary',
                'correct_option' => 'B',
            ],
            [
                'question' => 'In 3D game engines, what is the process of hiding objects that are not within the camera\'s view frustum to save CPU/GPU rendering resources?',
                'option_a' => 'A) Frustum Culling',
                'option_b' => 'B) Occlusion Culling',
                'option_c' => 'C) Raycasting',
                'option_d' => 'D) Mipmapping',
                'correct_option' => 'A',
            ],
            [
                'question' => 'Which operator is standard to compare two values for equality in C#?',
                'option_a' => 'A) =',
                'option_b' => 'B) ==',
                'option_c' => 'C) !=',
                'option_d' => 'D) ===',
                'correct_option' => 'B',
            ],
            [
                'question' => 'What does the term "Prefab" refer to in the Unity Editor?',
                'option_a' => 'A) A compiled C# script asset',
                'option_b' => 'B) A reusable template asset of a GameObject',
                'option_c' => 'C) A compressed audio format',
                'option_d' => 'D) A materials graphics shader',
                'correct_option' => 'B',
            ],
            [
                'question' => 'What type of variable can hold only two values: true or false?',
                'option_a' => 'A) Integer',
                'option_b' => 'B) Float',
                'option_c' => 'C) Boolean',
                'option_d' => 'D) String',
                'correct_option' => 'C',
            ],
        ];

        foreach ($questions as $q) {
            AssessmentQuestion::create($q);
        }
    }
}
