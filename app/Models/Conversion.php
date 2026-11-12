<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Conversion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'original_filename',
        'original_size',
        'source_version',
        'converted_filename',
        'converted_file_path',
        'converted_size',
        'target_version',
        'conversion_duration',
        'status',
        'error_message',
        'downloaded',
        'downloaded_at',
        'expires_at'
    ];

    protected $casts = [
        'original_size' => 'integer',
        'converted_size' => 'integer',
        'conversion_duration' => 'decimal:2',
        'downloaded' => 'boolean',
        'downloaded_at' => 'datetime',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Vérifier si le fichier existe encore
     */
    public function fileExists(): bool
    {
        return file_exists($this->converted_file_path);
    }

    /**
     * Obtenir la taille formatée
     */
    public function getFormattedOriginalSizeAttribute(): string
    {
        return $this->formatBytes($this->original_size);
    }

    public function getFormattedConvertedSizeAttribute(): string
    {
        return $this->formatBytes($this->converted_size);
    }

    /**
     * Formater les bytes
     */
    protected function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }


    /**
     * ✅ Relation avec la transaction de coin (NOUVEAU)
     * Une conversion peut avoir UNE transaction associée
     */
    public function coinTransaction()
    {
        return $this->hasOne(CoinTransaction::class);
    }


    /**
     * Vérifier si le fichier a expiré
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && now()->isAfter($this->expires_at);
    }

    /**
     * Supprimer le fichier physique
     */
    public function deleteFile(): bool
    {
        if ($this->fileExists()) {
            return unlink($this->converted_file_path);
        }
        return false;
    }

    /**
     * Scope pour obtenir les conversions d'une session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope pour obtenir les conversions d'un utilisateur
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope pour obtenir les conversions récentes
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope pour obtenir les conversions complétées
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope pour obtenir les conversions non expirées
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }


    /**
     * Afficher la version source de manière intelligente
     */
    /*public function getDisplaySourceVersionAttribute(): string
    {
        $version = (int)$this->source_version;
        if ($version >= 2021) {
            return 'Versionless (2021+)';
        }
        return $this->source_version;
    }

    public function getDisplayTargetVersionAttribute(): string
    {
        $version = (int)$this->target_version;
        if ($version >= 2021) {
            return $this->target_version . ' (compatible 2021+)';
        }
        return $this->target_version;
    }*/

        /**
        * Vérifier si le fichier est encore téléchargeable (moins de 3 jours)
        */
        public function isDownloadable(): bool
        {
            return $this->created_at->isAfter(now()->subDays(3))
                && $this->converted_file_path !== null
                && $this->fileExists();
        }
        
}