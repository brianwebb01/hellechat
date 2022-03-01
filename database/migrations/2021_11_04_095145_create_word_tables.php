<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWordTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('words_adjectives', function (Blueprint $table) {
            $table->string('word');
        });

        Schema::create('words_animals', function (Blueprint $table) {
            $table->string('word');
        });

        DB::table('words_adjectives')->insert([
            ['word' => 'Calm'],
            ['word' => 'Centered'],
            ['word' => 'Content'],
            ['word' => 'Fulfilled'],
            ['word' => 'Patient'],
            ['word' => 'Peaceful'],
            ['word' => 'Present'],
            ['word' => 'Relaxed'],
            ['word' => 'Serene'],
            ['word' => 'Trusting'],
            ['word' => 'Amazed'],
            ['word' => 'Delighted'],
            ['word' => 'Eager'],
            ['word' => 'Ecstatic'],
            ['word' => 'Enchanted'],
            ['word' => 'Energized'],
            ['word' => 'Engaged'],
            ['word' => 'Enthusiastic'],
            ['word' => 'Excited'],
            ['word' => 'Happy'],
            ['word' => 'Inspired'],
            ['word' => 'Invigorated'],
            ['word' => 'Lively'],
            ['word' => 'Passionate'],
            ['word' => 'Playful'],
            ['word' => 'Radiant'],
            ['word' => 'Refreshed'],
            ['word' => 'Rejuvenated'],
            ['word' => 'Renewed'],
            ['word' => 'Satisfied'],
            ['word' => 'Thrilled'],
            ['word' => 'Vibrant'],
            ['word' => 'Agitated'],
            ['word' => 'Aggravated'],
            ['word' => 'Cynical'],
            ['word' => 'Disgruntled'],
            ['word' => 'Disturbed'],
            ['word' => 'Edgy'],
            ['word' => 'Exasperated'],
            ['word' => 'Frustrated'],
            ['word' => 'Furious'],
            ['word' => 'Grouchy'],
            ['word' => 'Impatient'],
            ['word' => 'Resentful'],
            ['word' => 'Upset'],
            ['word' => 'Vindictive'],
            ['word' => 'Adventurous'],
            ['word' => 'Brave'],
            ['word' => 'Capable'],
            ['word' => 'Confident'],
            ['word' => 'Daring'],
            ['word' => 'Determined'],
            ['word' => 'Free'],
            ['word' => 'Grounded'],
            ['word' => 'Proud'],
            ['word' => 'Strong'],
            ['word' => 'Worthy'],
            ['word' => 'Valiant'],
            ['word' => 'Accepting'],
            ['word' => 'Affectionate'],
            ['word' => 'Caring'],
            ['word' => 'Compassion'],
            ['word' => 'Fulfilled'],
            ['word' => 'Present'],
            ['word' => 'Safe'],
            ['word' => 'Warm'],
            ['word' => 'Worthy'],
            ['word' => 'Curious'],
            ['word' => 'Engaged'],
            ['word' => 'Exploring'],
            ['word' => 'Fascinated'],
            ['word' => 'Interested'],
            ['word' => 'Intrigued'],
            ['word' => 'Involved'],
            ['word' => 'Stimulated'],
            ['word' => 'Sensitive'],
            ['word' => 'Grateful'],
            ['word' => 'Appreciative'],
            ['word' => 'Blessed'],
            ['word' => 'Delighted'],
            ['word' => 'Fortunate'],
            ['word' => 'Humbled'],
            ['word' => 'Lucky'],
            ['word' => 'Moved'],
            ['word' => 'Thankful'],
            ['word' => 'Touched'],
            ['word' => 'Remorseful'],
            ['word' => 'Sorry'],
            ['word' => 'Hopeful'],
            ['word' => 'Encouraged'],
            ['word' => 'Expectant'],
            ['word' => 'Optimistic'],
            ['word' => 'Trusting'],
            ['word' => 'Powerless'],
            ['word' => 'Impotent'],
            ['word' => 'Incapable'],
            ['word' => 'Resigned'],
            ['word' => 'Tender'],
            ['word' => 'Calm'],
            ['word' => 'Caring'],
            ['word' => 'Loving'],
            ['word' => 'Reflective'],
            ['word' => 'Serene'],
            ['word' => 'Vulnerable'],
            ['word' => 'Warm'],
            ['word' => 'Anxious'],
            ['word' => 'Cranky'],
            ['word' => 'Depleted'],
            ['word' => 'Edgy'],
            ['word' => 'Exhausted'],
            ['word' => 'Frazzled'],
            ['word' => 'Overwhelm'],
            ['word' => 'Rattled'],
            ['word' => 'Rejecting'],
            ['word' => 'Restless'],
            ['word' => 'Shaken'],
            ['word' => 'Tight'],
            ['word' => 'Weary'],
            ['word' => 'Apprehensive'],
            ['word' => 'Concerned'],
            ['word' => 'Dissatisfied'],
            ['word' => 'Disturbed'],
            ['word' => 'Grouchy'],
            ['word' => 'Hesitant'],
            ['word' => 'Inhibited'],
            ['word' => 'Perplexed'],
            ['word' => 'Questioning'],
            ['word' => 'Rejecting'],
            ['word' => 'Reluctant'],
            ['word' => 'Shocked'],
            ['word' => 'Skeptical'],
            ['word' => 'Suspicious'],
            ['word' => 'Ungrounded'],
            ['word' => 'Unsure'],
            ['word' => 'Worried'],
        ]);

        DB::table('words_animals')->insert([
            ['word' => 'Aardvark'],
            ['word' => 'Aardwolf'],
            ['word' => 'Buffalo'],
            ['word' => 'Elephant'],
            ['word' => 'Leopard'],
            ['word' => 'Albatross'],
            ['word' => 'Alligator'],
            ['word' => 'Alpaca'],
            ['word' => 'Bison'],
            ['word' => 'Robin'],
            ['word' => 'Amphibian'],
            ['word' => 'Anaconda'],
            ['word' => 'Angelfish'],
            ['word' => 'Anglerfish'],
            ['word' => 'Ant'],
            ['word' => 'Anteater'],
            ['word' => 'Antelope'],
            ['word' => 'Antlion'],
            ['word' => 'Ape'],
            ['word' => 'Aphid'],
            ['word' => 'Fox'],
            ['word' => 'Wolf'],
            ['word' => 'Armadillo'],
            ['word' => 'Crab'],
            ['word' => 'Asp'],
            ['word' => 'Baboon'],
            ['word' => 'Badger'],
            ['word' => 'Eagle'],
            ['word' => 'Bandicoot'],
            ['word' => 'Barnacle'],
            ['word' => 'Barracuda'],
            ['word' => 'Basilisk'],
            ['word' => 'Bass'],
            ['word' => 'Bat'],
            ['word' => 'Bear'],
            ['word' => 'Beaver'],
            ['word' => 'Bedbug'],
            ['word' => 'Bee'],
            ['word' => 'Beetle'],
            ['word' => 'Bird'],
            ['word' => 'Bison'],
            ['word' => 'Blackbird'],
            ['word' => 'Panther'],
            ['word' => 'Boa'],
            ['word' => 'Boar'],
            ['word' => 'Bobcat'],
            ['word' => 'Bobolink'],
            ['word' => 'Bonobo'],
            ['word' => 'Booby'],
            ['word' => 'Bovid'],
            ['word' => 'Bug'],
            ['word' => 'Butterfly'],
            ['word' => 'Buzzard'],
            ['word' => 'Camel'],
            ['word' => 'Canid'],
            ['word' => 'Capybara'],
            ['word' => 'Cardinal'],
            ['word' => 'Caribou'],
            ['word' => 'Carp'],
            ['word' => 'Cat'],
            ['word' => 'Catshark'],
            ['word' => 'Caterpillar'],
            ['word' => 'Catfish'],
            ['word' => 'Cattle'],
            ['word' => 'Centipede'],
            ['word' => 'Cephalopod'],
            ['word' => 'Chameleon'],
            ['word' => 'Cheetah'],
            ['word' => 'Chickadee'],
            ['word' => 'Chimpanzee'],
            ['word' => 'Chinchilla'],
            ['word' => 'Chipmunk'],
            ['word' => 'Clam'],
            ['word' => 'Clownfish'],
            ['word' => 'Cobra'],
            ['word' => 'Cockroach'],
            ['word' => 'Cod'],
            ['word' => 'Condor'],
            ['word' => 'Constrictor'],
            ['word' => 'Coral'],
            ['word' => 'Cougar'],
            ['word' => 'Cow'],
            ['word' => 'Coyote'],
            ['word' => 'Crab'],
            ['word' => 'Crane'],
            ['word' => 'Crane fly'],
            ['word' => 'Crawdad'],
            ['word' => 'Crayfish'],
            ['word' => 'Cricket'],
            ['word' => 'Crocodile'],
            ['word' => 'Crow'],
            ['word' => 'Cuckoo'],
            ['word' => 'Cicada'],
            ['word' => 'Damselfly'],
            ['word' => 'Deer'],
            ['word' => 'Dingo'],
            ['word' => 'Dinosaur'],
            ['word' => 'Dog'],
            ['word' => 'Dolphin'],
            ['word' => 'Donkey'],
            ['word' => 'Dormouse'],
            ['word' => 'Dove'],
            ['word' => 'Dragonfly'],
            ['word' => 'Dragon'],
            ['word' => 'Duck'],
            ['word' => 'Eagle'],
            ['word' => 'Earthworm'],
            ['word' => 'Earwig'],
            ['word' => 'Echidna'],
            ['word' => 'Eel'],
            ['word' => 'Egret'],
            ['word' => 'Elephant'],
            ['word' => 'Elk'],
            ['word' => 'Emu'],
            ['word' => 'Ermine'],
            ['word' => 'Falcon'],
            ['word' => 'Ferret'],
            ['word' => 'Finch'],
            ['word' => 'Firefly'],
            ['word' => 'Fish'],
            ['word' => 'Flamingo'],
            ['word' => 'Flea'],
            ['word' => 'Fly'],
            ['word' => 'Flyingfish'],
            ['word' => 'Fowl'],
            ['word' => 'Fox'],
            ['word' => 'Frog'],
            ['word' => 'Gamefowl'],
            ['word' => 'Galliform'],
            ['word' => 'Gazelle'],
            ['word' => 'Gecko'],
            ['word' => 'Gerbil'],
            ['word' => 'Gibbon'],
            ['word' => 'Giraffe'],
            ['word' => 'Goat'],
            ['word' => 'Goldfish'],
            ['word' => 'Goose'],
            ['word' => 'Gopher'],
            ['word' => 'Gorilla'],
            ['word' => 'Grasshopper'],
            ['word' => 'Grouse'],
            ['word' => 'Guan'],
            ['word' => 'Guanaco'],
            ['word' => 'Gull'],
            ['word' => 'Guppy'],
            ['word' => 'Haddock'],
            ['word' => 'Halibut'],
            ['word' => 'Hamster'],
            ['word' => 'Hare'],
            ['word' => 'Harrier'],
            ['word' => 'Hawk'],
            ['word' => 'Hedgehog'],
            ['word' => 'Heron'],
            ['word' => 'Herring'],
            ['word' => 'Hippopo'],
            ['word' => 'Hornet'],
            ['word' => 'Horse'],
            ['word' => 'Hoverfly'],
            ['word' => 'Hummingbird'],
            ['word' => 'Hyena'],
            ['word' => 'Iguana'],
            ['word' => 'Impala'],
            ['word' => 'Jackal'],
            ['word' => 'Jaguar'],
            ['word' => 'Jay'],
            ['word' => 'Jellyfish'],
            ['word' => 'Junglefowl'],
            ['word' => 'Kangaroo'],
            ['word' => 'Kingfisher'],
            ['word' => 'Kite'],
            ['word' => 'Kiwi'],
            ['word' => 'Koala'],
            ['word' => 'Koi'],
            ['word' => 'Krill'],
            ['word' => 'Ladybug'],
            ['word' => 'Lamprey'],
            ['word' => 'Landfowl'],
            ['word' => 'Lark'],
            ['word' => 'Leech'],
            ['word' => 'Lemming'],
            ['word' => 'Lemur'],
            ['word' => 'Leopard'],
            ['word' => 'Leopon'],
            ['word' => 'Limpet'],
            ['word' => 'Lion'],
            ['word' => 'Lizard'],
            ['word' => 'Llama'],
            ['word' => 'Lobster'],
            ['word' => 'Locust'],
            ['word' => 'Loon'],
            ['word' => 'Louse'],
            ['word' => 'Lungfish'],
            ['word' => 'Lynx'],
            ['word' => 'Macaw'],
            ['word' => 'Mackerel'],
            ['word' => 'Magpie'],
            ['word' => 'Mammal'],
            ['word' => 'Manatee'],
            ['word' => 'Mandrill'],
            ['word' => 'Marlin'],
            ['word' => 'Marmoset'],
            ['word' => 'Marmot'],
            ['word' => 'Marsupial'],
            ['word' => 'Marten'],
            ['word' => 'Mastodon'],
            ['word' => 'Meadowlark'],
            ['word' => 'Meerkat'],
            ['word' => 'Mink'],
            ['word' => 'Minnow'],
            ['word' => 'Mite'],
            ['word' => 'Mockingbird'],
            ['word' => 'Mole'],
            ['word' => 'Mollusk'],
            ['word' => 'Mongoose'],
            ['word' => 'Monkey'],
            ['word' => 'Moose'],
            ['word' => 'Mosquito'],
            ['word' => 'Moth'],
            ['word' => 'Mouse'],
            ['word' => 'Mule'],
            ['word' => 'Muskox'],
            ['word' => 'Narwhal'],
            ['word' => 'Newt'],
            ['word' => 'Nightingale'],
            ['word' => 'Ocelot'],
            ['word' => 'Octopus'],
            ['word' => 'Opossum'],
            ['word' => 'Orangutan'],
            ['word' => 'Orca'],
            ['word' => 'Ostrich'],
            ['word' => 'Otter'],
            ['word' => 'Owl'],
            ['word' => 'Ox'],
            ['word' => 'Panda'],
            ['word' => 'Panther'],
            ['word' => 'Parakeet'],
            ['word' => 'Parrot'],
            ['word' => 'Parrotfish'],
            ['word' => 'Partridge'],
            ['word' => 'Peacock'],
            ['word' => 'Peafowl'],
            ['word' => 'Pelican'],
            ['word' => 'Penguin'],
            ['word' => 'Perch'],
            ['word' => 'Pheasant'],
            ['word' => 'Pig'],
            ['word' => 'Pigeon'],
            ['word' => 'Pike'],
            ['word' => 'Pinniped'],
            ['word' => 'Piranha'],
            ['word' => 'Planarian'],
            ['word' => 'Platypus'],
            ['word' => 'Pony'],
            ['word' => 'Porcupine'],
            ['word' => 'Porpoise'],
            ['word' => 'Possum'],
            ['word' => 'Prawn'],
            ['word' => 'Primate'],
            ['word' => 'Ptarmigan'],
            ['word' => 'Puffin'],
            ['word' => 'Puma'],
            ['word' => 'Python'],
            ['word' => 'Quail'],
            ['word' => 'Quelea'],
            ['word' => 'Quokka'],
            ['word' => 'Rabbit'],
            ['word' => 'Raccoon'],
            ['word' => 'Rat'],
            ['word' => 'Rattlesnake'],
            ['word' => 'Raven'],
            ['word' => 'Reindeer'],
            ['word' => 'Reptile'],
            ['word' => 'Rhinoceros'],
            ['word' => 'Roadrunner'],
            ['word' => 'Rodent'],
            ['word' => 'Rook'],
            ['word' => 'Rooster'],
            ['word' => 'Roundworm'],
            ['word' => 'Sailfish'],
            ['word' => 'Salamander'],
            ['word' => 'Salmon'],
            ['word' => 'Sawfish'],
            ['word' => 'Scallop'],
            ['word' => 'Scorpion'],
            ['word' => 'Seahorse'],
            ['word' => 'Shark'],
            ['word' => 'Sheep'],
            ['word' => 'Shrew'],
            ['word' => 'Shrimp'],
            ['word' => 'Silkworm'],
            ['word' => 'Silverfish'],
            ['word' => 'Skink'],
            ['word' => 'Skunk'],
            ['word' => 'Sloth'],
            ['word' => 'Slug'],
            ['word' => 'Smelt'],
            ['word' => 'Snail'],
            ['word' => 'Snake'],
            ['word' => 'Snipe'],
            ['word' => 'Sole'],
            ['word' => 'Sparrow'],
            ['word' => 'Spoonbill'],
            ['word' => 'Squid'],
            ['word' => 'Squirrel'],
            ['word' => 'Starfish'],
            ['word' => 'Stingray'],
            ['word' => 'Stoat'],
            ['word' => 'Stork'],
            ['word' => 'Sturgeon'],
            ['word' => 'Swallow'],
            ['word' => 'Swan'],
            ['word' => 'Swift'],
            ['word' => 'Swordfish'],
            ['word' => 'Swordtail'],
            ['word' => 'Tahr'],
            ['word' => 'Takin'],
            ['word' => 'Tapir'],
            ['word' => 'Tarantula'],
            ['word' => 'Tarsier'],
            ['word' => 'Termite'],
            ['word' => 'Tern'],
            ['word' => 'Thrush'],
            ['word' => 'Tick'],
            ['word' => 'Tiger'],
            ['word' => 'Tiglon'],
            ['word' => 'Toad'],
            ['word' => 'Tortoise'],
            ['word' => 'Toucan'],
            ['word' => 'Tree frog'],
            ['word' => 'Trout'],
            ['word' => 'Tuna'],
            ['word' => 'Turkey'],
            ['word' => 'Turtle'],
            ['word' => 'Urial'],
            ['word' => 'Vicuna'],
            ['word' => 'Viper'],
            ['word' => 'Vole'],
            ['word' => 'Vulture'],
            ['word' => 'Wallaby'],
            ['word' => 'Walrus'],
            ['word' => 'Wasp'],
            ['word' => 'Warbler'],
            ['word' => 'Weasel'],
            ['word' => 'Whale'],
            ['word' => 'Whippet'],
            ['word' => 'Whitefish'],
            ['word' => 'Wildcat'],
            ['word' => 'Wildebeest'],
            ['word' => 'Wildfowl'],
            ['word' => 'Wolf'],
            ['word' => 'Wolverine'],
            ['word' => 'Wombat'],
            ['word' => 'Woodpecker'],
            ['word' => 'Worm'],
            ['word' => 'Wren'],
            ['word' => 'Xerinae'],
            ['word' => 'Yak'],
            ['word' => 'Zebra'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('words_adjectives');
        Schema::dropIfExists('words_animals');
    }
}
